<?php

namespace Tests\Feature;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\TaskImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(array $overrides = []): User
    {
        $defaults = [
            'username' => 'tester',
            'email' => 'tester@example.com',
            'phone' => '0912345678',
            'password' => 'secret123',
        ];
        return User::create(array_merge($defaults, $overrides));
    }

    private function actingAsWithAbilities(User $user, array $abilities): void
    {
        Sanctum::actingAs($user, $abilities);
    }

    public function test_guest_can_list_tasks_and_show_task(): void
    {
        $user = $this->createUser();

        $taskA = Task::create([
            'title' => 'First Task',
            'description' => 'Alpha',
            'status' => TaskStatusEnum::PENDING->value,
            'user_id' => $user->id,
        ]);
        $taskB = Task::create([
            'title' => 'Second Task',
            'description' => 'Beta',
            'status' => TaskStatusEnum::COMPLETED->value,
            'user_id' => $user->id,
        ]);

        // index
        $indexResponse = $this->getJson('/api/dashboard/task');
        $indexResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['user', 'title', 'status']
                ]
            ]);

        // show
        TaskImage::create(['task_id' => $taskA->id, 'image' => 'task/img1.jpg']);
        $showResponse = $this->getJson('/api/dashboard/task/' . $taskA->id);
        $showResponse->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => ['user', 'title', 'description', 'status', 'images']
            ]);
    }

    public function test_store_requires_authentication_and_ability(): void
    {
        $payload = [
            'title' => 'New Task',
            'description' => 'Desc',
            'status' => TaskStatusEnum::PENDING->value,
        ];

        // Unauthenticated
        $this->postJson('/api/dashboard/task', $payload)
            ->assertStatus(401);

        // Authenticated without ability
        $user = $this->createUser(['username' => 'no-ability']);
        $this->actingAsWithAbilities($user, []);
        $this->postJson('/api/dashboard/task', $payload)
            ->assertStatus(403)
            ->assertJsonPath('error', 'NOT_AUTHORIZED');
    }

    public function test_store_creates_task_with_images(): void
    {
        Storage::fake('public');
        $user = $this->createUser(['username' => 'creator']);
        $this->actingAsWithAbilities($user, ['task::store']);

        $payload = [
            'title' => 'Task With Images',
            'description' => 'Has images',
            'status' => TaskStatusEnum::IN_PROGRESS->value,
            'images' => [
                UploadedFile::fake()->image('a.jpg'),
                UploadedFile::fake()->image('b.png'),
            ],
        ];

        $this->postJson('/api/dashboard/task', $payload)
            ->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Task With Images',
            'user_id' => $user->id,
        ]);
        $task = Task::where('title', 'Task With Images')->first();
        $this->assertNotNull($task);
        $this->assertDatabaseCount('task_images', 2);
        $this->assertEquals(2, $task->images()->count());
    }

    public function test_update_updates_fields_deletes_and_adds_images(): void
    {
        Storage::fake('public');
        $user = $this->createUser(['username' => 'updater']);
        $this->actingAsWithAbilities($user, ['task::update']);

        $task = Task::create([
            'title' => 'Old',
            'description' => 'Old Desc',
            'status' => TaskStatusEnum::PENDING->value,
            'user_id' => $user->id,
        ]);
        $img1 = TaskImage::create(['task_id' => $task->id, 'image' => 'task/old1.jpg']);
        TaskImage::create(['task_id' => $task->id, 'image' => 'task/old2.jpg']);

        $payload = [
            'title' => 'New',
            'description' => 'New Desc',
            'status' => TaskStatusEnum::COMPLETED->value,
            'delete_image_ids' => [$img1->id],
            'images' => [UploadedFile::fake()->image('c.webp')],
        ];

        $this->putJson('/api/dashboard/task/' . $task->id, $payload)
            ->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $task->refresh();
        $this->assertEquals('New', $task->title);
        $this->assertEquals('New Desc', $task->description);
        $this->assertEquals(TaskStatusEnum::COMPLETED->value, $task->status->value);
        $this->assertDatabaseMissing('task_images', ['id' => $img1->id]);
        $this->assertEquals(2, $task->images()->count());
    }

    public function test_update_requires_ability(): void
    {
        $user = $this->createUser(['username' => 'no-update-ability']);
        $this->actingAsWithAbilities($user, []);
        $task = Task::create([
            'title' => 'T',
            'description' => 'D',
            'status' => TaskStatusEnum::PENDING->value,
            'user_id' => $user->id,
        ]);

        $this->putJson('/api/dashboard/task/' . $task->id, [
            'title' => 'X',
            'description' => 'Y',
        ])->assertStatus(403)->assertJsonPath('error', 'NOT_AUTHORIZED');
    }

    public function test_delete_deletes_task_and_requires_ability(): void
    {
        $user = $this->createUser(['username' => 'deleter']);
        $task = Task::create([
            'title' => 'Del',
            'description' => 'To Del',
            'status' => TaskStatusEnum::PENDING->value,
            'user_id' => $user->id,
        ]);

        // No ability
        Sanctum::actingAs($user, []);
        $this->deleteJson('/api/dashboard/task/' . $task->id)
            ->assertStatus(403)
            ->assertJsonPath('error', 'NOT_AUTHORIZED');

        // With ability
        Sanctum::actingAs($user, ['task::delete']);
        $this->deleteJson('/api/dashboard/task/' . $task->id)
            ->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}

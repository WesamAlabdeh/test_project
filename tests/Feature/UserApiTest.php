<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(array $overrides = []): User
    {
        $defaults = [
            'username' => 'admin_' . uniqid(),
            'email' => 'admin+' . uniqid() . '@example.com',
            'phone' => '09' . rand(100000000, 999999999),
            'password' => 'secret123',
        ];
        return User::create(array_merge($defaults, $overrides));
    }

    private function authAs(array $abilities): User
    {
        $admin = $this->createAdmin();
        Sanctum::actingAs($admin, $abilities);
        return $admin;
    }

    public function test_index_requires_ability(): void
    {
        $this->authAs([]);
        $this->getJson('/api/dashboard/user')
            ->assertStatus(403)
            ->assertJsonPath('error', 'NOT_AUTHORIZED');
    }

    public function test_index_lists_users(): void
    {
        $this->authAs(['user::index']);
        User::create([
            'username' => 'u1',
            'email' => 'u1@example.com',
            'phone' => '0922222222',
            'password' => 'secret123',
        ]);
        $this->getJson('/api/dashboard/user')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['username', 'email', 'phone']
                ]
            ]);
    }

    public function test_store_validates_and_requires_ability(): void
    {
        $this->authAs([]);
        $this->postJson('/api/dashboard/user', [])
            ->assertStatus(403)
            ->assertJsonPath('error', 'NOT_AUTHORIZED');

        $this->authAs(['user::store']);
        $this->postJson('/api/dashboard/user', [
            'username' => 'new',
            'email' => 'invalid',
            'password' => 'short',
            'phone' => 'not-phone',
        ])->assertStatus(422);

        $this->postJson('/api/dashboard/user', [
            'username' => 'new',
            'email' => 'new@example.com',
            'password' => 'secret123',
            'phone' => '0900000000',
        ])->assertStatus(200)->assertJsonPath('status', 'success');
    }

    public function test_show_update_delete_require_abilities(): void
    {
        $target = User::create([
            'username' => 'target',
            'email' => 'target@example.com',
            'phone' => '0933333333',
            'password' => 'secret123',
        ]);

        $this->authAs([]);
        $this->getJson('/api/dashboard/user/' . $target->id)->assertStatus(403);
        $this->putJson('/api/dashboard/user/' . $target->id, [
            'username' => 't2',
        ])->assertStatus(403);
        $this->deleteJson('/api/dashboard/user/' . $target->id)->assertStatus(403);

        $this->authAs(['user::show']);
        $this->getJson('/api/dashboard/user/' . $target->id)
            ->assertStatus(200)
            ->assertJsonStructure(['status', 'data' => ['username', 'email', 'phone']]);

        $this->authAs(['user::update']);
        $this->putJson('/api/dashboard/user/' . $target->id, [
            'username' => 't2',
            'email' => 't2@example.com',
            'phone' => '0944444444',
        ])->assertStatus(200);

        $this->authAs(['user::delete']);
        $this->deleteJson('/api/dashboard/user/' . $target->id)
            ->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }
}

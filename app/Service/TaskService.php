<?php

namespace App\Service;

use App\Events\TaskStatusUpdated;
use App\Models\Task;
use App\Models\TaskImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Task::class);
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            $taskData = $this->assignAuthenticatedUserId($data);
            $task = parent::store($taskData);

            if (!empty($data['images'])) {
                $this->attachImages($task, $data['images']);
            }

            return true;
        });
    }


    public function update($id, $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $this->assignAuthenticatedUserId($data);

            $updatableData = collect($data)->except(['images', 'delete_image_ids', 'user_id'])->toArray();
            $originalTask = Task::findOrFail($id);
            $oldStatus = (string) $originalTask->status;

            $task = parent::update($id, $updatableData);

            if (!empty($data['delete_image_ids']) && is_array($data['delete_image_ids'])) {
                $this->deleteImages($task, $data['delete_image_ids']);
            }

            if (!empty($data['images']) && is_array($data['images'])) {
                $this->attachImages($task, $data['images']);
            }

            if (array_key_exists('status', $updatableData)) {
                $newStatus = (string) $task->status;
                if ($newStatus !== $oldStatus) {
                    event(new TaskStatusUpdated($task, $oldStatus, $newStatus));
                }
            }

            return true;
        });
    }


    private function assignAuthenticatedUserId(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }


    private function attachImages(Task $task, array $images): void
    {
        $stored = $this->storeFiles(['images' => $images], 'images', 'task');
        $imagePaths = $stored['images'] ?? [];

        foreach ($imagePaths as $path) {
            TaskImage::create([
                'task_id' => $task->id,
                'image' => $path,
            ]);
        }
    }

    private function deleteImages(Task $task, array $imageIds): void
    {
        $images = TaskImage::where('task_id', $task->id)
            ->whereIn('id', $imageIds)
            ->get();

        foreach ($images as $image) {
            $image->delete();
        }
    }
}

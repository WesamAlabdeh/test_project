<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Task\CreateTaskRequest;
use App\Http\Requests\User\Task\UpdateTaskRequest;
use App\Http\Resources\User\Task\ShowTaskResource;
use App\Http\Resources\User\Task\TaskResource;
use App\Service\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService) {}

    public function index(Request $request)
    {
        $filters = $request->all();
        $tasks = $this->taskService->index($filters, ['user']);
        return $this->success(TaskResource::collection($tasks));
    }
    public function show($id)
    {
        $task =  $this->taskService->show($id, ['user', 'images']);
        return $this->success(new ShowTaskResource($task));
    }

    public function store(CreateTaskRequest $createTaskRequest)
    {
        $data = $createTaskRequest->validated();
        $this->taskService->store($data);
        return $this->success();
    }

    public function update($id, UpdateTaskRequest $request)
    {
        $data = $request->validated();
        $this->taskService->update($id, $data);
        return $this->success();
    }

    public function delete($id)
    {
        $this->taskService->delete($id);
        return $this->success();
    }
}

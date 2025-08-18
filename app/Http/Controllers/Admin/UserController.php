<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\CreateUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Resources\Admin\User\ShowUserResource;
use App\Http\Resources\Admin\User\UserResource;
use App\Service\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $taskService) {}

    public function index(Request $request)
    {
        $filters = $request->all();
        $tasks = $this->taskService->index($filters);
        return $this->success(UserResource::collection($tasks));
    }
    public function show($id)
    {
        $task =  $this->taskService->show($id);
        return $this->success(new ShowUserResource($task));
    }

    public function store(CreateUserRequest $createTaskRequest)
    {
        $data = $createTaskRequest->validated();
        $this->taskService->store($data);
        return $this->success();
    }

    public function update($id, UpdateUserRequest $request)
    {
        $data = $request->validated();
        $this->taskService->update($id, $data);
        return $this->success();
    }

    public function delete($id)
    {
        $this->taskService->delete($id);
        return $this->success();
    }}

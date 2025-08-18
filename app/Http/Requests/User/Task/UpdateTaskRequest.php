<?php

namespace App\Http\Requests\User\Task;

use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['sometimes', 'in:' . implode(',', TaskStatusEnum::values())],
            'images.*' => ['sometimes', 'image', 'mimes:jpg,png,webp'],
            'delete_image_ids' => ['sometimes', 'array'],
            'delete_image_ids.*' => ['integer', 'exists:task_images,id']
        ];
    }
}

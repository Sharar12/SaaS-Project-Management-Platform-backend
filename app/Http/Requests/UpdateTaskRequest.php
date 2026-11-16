<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:200'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'in:backlog,todo,in_progress,in_review,done'],
            'priority' => ['sometimes', 'in:low,medium,high,urgent'],
            'assigneeId' => ['sometimes', 'nullable', 'string', 'exists:team_members,id'],
            'startDate' => ['sometimes', 'date'],
            'dueDate' => ['sometimes', 'date'],
            'tags' => ['sometimes', 'array'],
            'estimatedHours' => ['sometimes', 'integer', 'min:0'],
            'subtasks' => ['sometimes', 'array'],
            'subtasks.*.id' => ['nullable', 'string'],
            'subtasks.*.title' => ['required_with:subtasks', 'string', 'max:200'],
            'subtasks.*.completed' => ['required_with:subtasks', 'boolean'],
            'actorId' => ['nullable', 'string', 'exists:team_members,id'],
            'activityNote' => ['nullable', 'string', 'max:120'],
        ];
    }
}

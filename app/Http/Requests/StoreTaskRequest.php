<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'workspaceId' => ['required', 'string', 'exists:workspaces,id'],
            'projectId' => ['required', 'string', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:backlog,todo,in_progress,in_review,done'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'assigneeId' => ['nullable', 'string', 'exists:team_members,id'],
            'startDate' => ['required', 'date'],
            'dueDate' => ['required', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:40'],
            'estimatedHours' => ['nullable', 'integer', 'min:0', 'max:2000'],
            'actorId' => ['nullable', 'string', 'exists:team_members,id'],
        ];
    }
}

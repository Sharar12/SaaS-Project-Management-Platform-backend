<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'workspaceId' => $this->workspace_id,
            'projectId' => $this->project_id,
            'code' => $this->code,
            'title' => $this->title,
            'description' => $this->description ?? '',
            'status' => $this->status,
            'priority' => $this->priority,
            'assigneeId' => $this->assignee_id,
            'startDate' => $this->start_date->format('Y-m-d'),
            'dueDate' => $this->due_date->format('Y-m-d'),
            'tags' => $this->tags ?? [],
            'subtasks' => SubtaskResource::collection($this->whenLoaded('subtasks')),
            'comments' => TaskCommentResource::collection($this->whenLoaded('comments')),
            'estimatedHours' => (int) $this->estimated_hours,
            'createdAt' => $this->created_at?->toISOString(),
        ];
    }
}

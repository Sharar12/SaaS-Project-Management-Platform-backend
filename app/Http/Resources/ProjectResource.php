<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'workspaceId' => $this->workspace_id,
            'key' => $this->key,
            'name' => $this->name,
            'description' => $this->description ?? '',
            'status' => $this->status,
            'color' => $this->color,
            'startDate' => $this->start_date->format('Y-m-d'),
            'dueDate' => $this->due_date->format('Y-m-d'),
            'leadId' => $this->lead_id ?? '',
            'memberIds' => $this->whenLoaded('members', fn () => $this->members->pluck('id')->values(), []),
            'tags' => $this->tags ?? [],
        ];
    }
}

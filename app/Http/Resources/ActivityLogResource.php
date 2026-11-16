<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'workspaceId' => $this->workspace_id,
            'actorId' => $this->actor_id ?? '',
            'action' => $this->action,
            'targetTitle' => $this->target_title,
            'timestamp' => $this->created_at?->toISOString(),
        ];
    }
}

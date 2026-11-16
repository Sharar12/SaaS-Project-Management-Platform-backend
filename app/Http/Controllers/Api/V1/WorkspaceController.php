<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWorkspaceRequest;
use App\Http\Resources\WorkspaceResource;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;

class WorkspaceController extends Controller
{
    public function update(UpdateWorkspaceRequest $request, Workspace $workspace): JsonResponse
    {
        $data = $request->validated();

        $workspace->update([
            'name' => $data['name'] ?? $workspace->name,
            'industry' => $data['industry'] ?? $workspace->industry,
            'plan' => $data['plan'] ?? $workspace->plan,
            'accent_color' => $data['accentColor'] ?? $workspace->accent_color,
        ]);

        return response()->json(new WorkspaceResource($workspace->fresh()));
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWorkspaceRequest;
use App\Http\Resources\WorkspaceResource;
use App\Models\TeamMember;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkspaceController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'industry' => ['nullable', 'string', 'max:120'],
            'plan' => ['nullable', 'in:free,pro,enterprise'],
            'accentColor' => ['nullable', 'string', 'max:30'],
        ]);

        $user = $request->user();
        $slug = Str::slug($data['name']) . '-' . Str::lower(Str::random(5));

        $workspace = Workspace::create([
            'id' => 'ws-' . Str::ulid(),
            'name' => $data['name'],
            'slug' => $slug,
            'industry' => $data['industry'] ?? 'Software & Technology',
            'plan' => $data['plan'] ?? 'pro',
            'accent_color' => $data['accentColor'] ?? '#4F46E5',
        ]);

        // Add creator as owner
        $parts = preg_split('/\s+/', trim($user->name)) ?: [];
        $initials = strtoupper(
            ($parts[0][0] ?? 'O') . ($parts[1][0] ?? ($parts[0][1] ?? 'W'))
        );

        TeamMember::create([
            'id' => 'mem-' . Str::ulid(),
            'workspace_id' => $workspace->id,
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'owner',
            'title' => 'Company Founder',
            'avatar_color' => 'bg-indigo-600',
            'initials' => $initials,
            'status' => 'online',
        ]);

        return response()->json(new WorkspaceResource($workspace), 201);
    }

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

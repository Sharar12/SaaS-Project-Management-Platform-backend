<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\InviteMemberRequest;
use App\Http\Requests\UpdateMemberRoleRequest;
use App\Http\Resources\TeamMemberResource;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function store(InviteMemberRequest $request): JsonResponse
    {
        $data = $request->validated();
        $palette = [
            'bg-indigo-600',
            'bg-sky-600',
            'bg-emerald-600',
            'bg-amber-600',
            'bg-rose-600',
            'bg-violet-600',
        ];

        $parts = preg_split('/\s+/', trim($data['name'])) ?: [];
        $initials = strtoupper(
            ($parts[0][0] ?? 'T') . ($parts[1][0] ?? ($parts[0][1] ?? 'M'))
        );

        $count = TeamMember::where('workspace_id', $data['workspaceId'])->count();

        $member = TeamMember::create([
            'id' => 'mem-' . Str::ulid(),
            'workspace_id' => $data['workspaceId'],
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'title' => $data['title'] ?? 'Product Contributor',
            'avatar_color' => $palette[$count % count($palette)],
            'initials' => $initials,
            'status' => 'online',
        ]);

        return response()->json(new TeamMemberResource($member), 201);
    }

    public function updateRole(UpdateMemberRoleRequest $request, TeamMember $member): JsonResponse
    {
        $member->update(['role' => $request->validated()['role']]);
        return response()->json(new TeamMemberResource($member->fresh()));
    }
}

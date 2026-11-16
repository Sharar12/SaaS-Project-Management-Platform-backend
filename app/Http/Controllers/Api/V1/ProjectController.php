<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\ActivityLog;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Project::with('members')->orderByDesc('created_at');
        if ($request->filled('workspaceId')) {
            $query->where('workspace_id', $request->string('workspaceId'));
        }
        return response()->json(ProjectResource::collection($query->get()));
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $data = $request->validated();

        $project = DB::transaction(function () use ($data) {
            $project = Project::create([
                'id' => 'proj-' . Str::ulid(),
                'workspace_id' => $data['workspaceId'],
                'key' => strtoupper(substr($data['key'], 0, 6)),
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'status' => $data['status'],
                'color' => $data['color'],
                'start_date' => $data['startDate'],
                'due_date' => $data['dueDate'],
                'lead_id' => $data['leadId'] ?? null,
                'tags' => $data['tags'] ?? [],
            ]);

            if (!empty($data['leadId'])) {
                $project->members()->sync([$data['leadId']]);
            }

            ActivityLog::create([
                'id' => 'act-' . Str::ulid(),
                'workspace_id' => $project->workspace_id,
                'actor_id' => $data['leadId'] ?? null,
                'action' => 'created project',
                'target_title' => $project->name,
            ]);

            return $project->load('members');
        });

        return response()->json(new ProjectResource($project), 201);
    }
}

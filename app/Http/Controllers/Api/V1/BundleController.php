<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TeamMemberResource;
use App\Http\Resources\WorkspaceResource;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TeamMember;
use App\Models\Workspace;
use Database\Seeders\OrbitFlowDemoSeeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BundleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get user memberships
        $memberships = TeamMember::where('user_id', $user->id)->get()->keyBy('workspace_id');
        $workspaceIds = $memberships->keys();

        // If user has memberships, scope to those; otherwise if admin/owner or demo, show workspaces
        if ($workspaceIds->isEmpty()) {
            $workspaces = Workspace::orderBy('created_at')->get();
        } else {
            $workspaces = Workspace::whereIn('id', $workspaceIds)->orderBy('created_at')->get();
        }

        $workspacesData = $workspaces->map(function (Workspace $ws) use ($memberships) {
            return [
                'id' => $ws->id,
                'name' => $ws->name,
                'slug' => $ws->slug,
                'industry' => $ws->industry,
                'plan' => $ws->plan,
                'accentColor' => $ws->accent_color,
                'myRole' => $memberships[$ws->id]->role ?? 'owner',
                'createdAt' => $ws->created_at?->toISOString(),
            ];
        })->values();

        $requestedWsId = $request->string('workspaceId')->toString();
        $activeWorkspaceId = ($requestedWsId && $workspaces->contains('id', $requestedWsId))
            ? $requestedWsId
            : ($workspacesData[0]['id'] ?? null);

        $projectsQuery = Project::with('members')->orderByDesc('created_at');
        $tasksQuery = Task::with(['subtasks', 'comments'])->orderByDesc('created_at');
        $membersQuery = TeamMember::orderBy('created_at');
        $activitiesQuery = ActivityLog::orderByDesc('created_at')->limit(40);

        if ($activeWorkspaceId) {
            $projectsQuery->where('workspace_id', $activeWorkspaceId);
            $tasksQuery->where('workspace_id', $activeWorkspaceId);
            $membersQuery->where('workspace_id', $activeWorkspaceId);
            $activitiesQuery->where('workspace_id', $activeWorkspaceId);
        }

        return response()->json([
            'user' => [
                'id' => (string) $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'workspaces' => $workspacesData,
            'activeWorkspaceId' => $activeWorkspaceId,
            'projects' => ProjectResource::collection($projectsQuery->get()),
            'tasks' => TaskResource::collection($tasksQuery->get()),
            'members' => TeamMemberResource::collection($membersQuery->get()),
            'activities' => ActivityLogResource::collection($activitiesQuery->get()),
        ]);
    }

    public function reset(): JsonResponse
    {
        abort_unless(config('app.debug') || env('DEMO_RESET_ENABLED', false), 403, 'Demo reset disabled.');

        DB::transaction(function () {
            ActivityLog::query()->delete();
            TaskComment::query()->delete();
            Subtask::query()->delete();
            Task::query()->delete();
            DB::table('project_member')->delete();
            Project::query()->delete();
            TeamMember::query()->delete();
            Workspace::query()->delete();
        });

        app(OrbitFlowDemoSeeder::class)->run();

        return response()->json(['message' => 'Database successfully reset to initial demo state.']);
    }
}

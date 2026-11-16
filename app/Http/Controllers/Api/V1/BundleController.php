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
use Illuminate\Support\Facades\DB;

class BundleController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'workspaces' => WorkspaceResource::collection(Workspace::orderBy('created_at')->get()),
            'projects' => ProjectResource::collection(
                Project::with('members')->orderByDesc('created_at')->get()
            ),
            'tasks' => TaskResource::collection(
                Task::with(['subtasks', 'comments'])->orderByDesc('created_at')->get()
            ),
            'members' => TeamMemberResource::collection(TeamMember::orderBy('created_at')->get()),
            'activities' => ActivityLogResource::collection(
                ActivityLog::orderByDesc('created_at')->limit(40)->get()
            ),
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

        return $this->index();
    }
}

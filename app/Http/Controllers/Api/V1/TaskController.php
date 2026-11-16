<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCommentRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Task::with(['subtasks', 'comments'])->orderByDesc('created_at');
        if ($request->filled('workspaceId')) {
            $query->where('workspace_id', $request->string('workspaceId'));
        }
        return response()->json(TaskResource::collection($query->get()));
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $data = $request->validated();

        $task = DB::transaction(function () use ($data) {
            $project = Project::findOrFail($data['projectId']);
            $existingCount = Task::where('project_id', $project->id)->count();
            $code = $project->key . '-' . (101 + $existingCount);

            $task = Task::create([
                'id' => 'task-' . Str::ulid(),
                'workspace_id' => $data['workspaceId'],
                'project_id' => $project->id,
                'code' => $code,
                'title' => $data['title'],
                'description' => $data['description'] ?? '',
                'status' => $data['status'],
                'priority' => $data['priority'],
                'assignee_id' => $data['assigneeId'] ?? null,
                'start_date' => $data['startDate'],
                'due_date' => $data['dueDate'],
                'tags' => $data['tags'] ?? [],
                'estimated_hours' => $data['estimatedHours'] ?? 0,
            ]);

            ActivityLog::create([
                'id' => 'act-' . Str::ulid(),
                'workspace_id' => $task->workspace_id,
                'actor_id' => $data['actorId'] ?? $data['assigneeId'] ?? null,
                'action' => 'created task',
                'target_title' => "{$task->code} {$task->title}",
            ]);

            return $task->load(['subtasks', 'comments']);
        });

        return response()->json(new TaskResource($task), 201);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $data = $request->validated();

        $updated = DB::transaction(function () use ($task, $data) {
            $task->update([
                'title' => $data['title'] ?? $task->title,
                'description' => array_key_exists('description', $data) ? $data['description'] : $task->description,
                'status' => $data['status'] ?? $task->status,
                'priority' => $data['priority'] ?? $task->priority,
                'assignee_id' => array_key_exists('assigneeId', $data) ? $data['assigneeId'] : $task->assignee_id,
                'start_date' => $data['startDate'] ?? $task->start_date,
                'due_date' => $data['dueDate'] ?? $task->due_date,
                'tags' => $data['tags'] ?? $task->tags,
                'estimated_hours' => $data['estimatedHours'] ?? $task->estimated_hours,
            ]);

            if (array_key_exists('subtasks', $data)) {
                $task->subtasks()->delete();
                foreach ($data['subtasks'] as $index => $sub) {
                    Subtask::create([
                        'id' => !empty($sub['id']) ? $sub['id'] : 'st-' . Str::ulid(),
                        'task_id' => $task->id,
                        'title' => $sub['title'],
                        'completed' => (bool) $sub['completed'],
                        'sort_order' => $index,
                    ]);
                }
            }

            if (!empty($data['activityNote'])) {
                ActivityLog::create([
                    'id' => 'act-' . Str::ulid(),
                    'workspace_id' => $task->workspace_id,
                    'actor_id' => $data['actorId'] ?? null,
                    'action' => $data['activityNote'],
                    'target_title' => "{$task->code} {$task->title}",
                ]);
            }

            return $task->fresh(['subtasks', 'comments']);
        });

        return response()->json(new TaskResource($updated));
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json(null, 204);
    }

    public function addComment(AddCommentRequest $request, Task $task): JsonResponse
    {
        $data = $request->validated();

        TaskComment::create([
            'id' => 'cm-' . Str::ulid(),
            'task_id' => $task->id,
            'author_id' => $data['authorId'],
            'content' => $data['content'],
        ]);

        return response()->json(new TaskResource($task->fresh(['subtasks', 'comments'])));
    }
}

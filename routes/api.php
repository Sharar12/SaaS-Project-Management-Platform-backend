<?php

use App\Http\Controllers\Api\V1\BundleController;
use App\Http\Controllers\Api\V1\MemberController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Workspace Bootstrap & Demo Reset
    Route::get('/bundle', [BundleController::class, 'index']);
    Route::post('/demo/reset', [BundleController::class, 'reset']);

    // Workspaces
    Route::patch('/workspaces/{workspace}', [WorkspaceController::class, 'update']);

    // Projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);

    // Tasks, Subtasks & Comments
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::patch('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::post('/tasks/{task}/comments', [TaskController::class, 'addComment']);

    // Team Members & RBAC Roles
    Route::post('/members', [MemberController::class, 'store']);
    Route::patch('/members/{member}/role', [MemberController::class, 'updateRole']);
});

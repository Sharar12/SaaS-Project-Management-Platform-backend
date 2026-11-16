<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BundleController;
use App\Http\Controllers\Api\V1\MemberController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public Auth
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Protected Routes (Sanctum)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Multi-Tenant Bundle
        Route::get('/bundle', [BundleController::class, 'index']);

        // Companies / Workspaces
        Route::post('/workspaces', [WorkspaceController::class, 'store']);
        Route::patch('/workspaces/{workspace}', [WorkspaceController::class, 'update']);

        // Company Members
        Route::post('/workspaces/{workspace}/members', [MemberController::class, 'store']);
        Route::patch('/workspaces/{workspace}/members/{member}/role', [MemberController::class, 'updateRole']);
        Route::delete('/workspaces/{workspace}/members/{member}', [MemberController::class, 'destroy']);

        // Projects
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::patch('/projects/{project}', [ProjectController::class, 'update']);
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

        // Tasks & Comments
        Route::post('/tasks', [TaskController::class, 'store']);
        Route::patch('/tasks/{task}', [TaskController::class, 'update']);
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
        Route::post('/tasks/{task}/comments', [TaskController::class, 'addComment']);
    });
});

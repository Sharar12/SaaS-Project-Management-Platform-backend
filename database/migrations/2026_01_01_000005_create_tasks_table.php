<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('workspace_id', 36);
            $table->string('project_id', 36);
            $table->string('code', 24);
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->enum('status', ['backlog', 'todo', 'in_progress', 'in_review', 'done'])
                ->default('todo');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])
                ->default('medium');
            $table->string('assignee_id', 36)->nullable();
            $table->date('start_date');
            $table->date('due_date');
            $table->json('tags')->nullable();
            $table->unsignedInteger('estimated_hours')->default(0);
            $table->timestamps();

            $table->foreign('workspace_id')
                ->references('id')->on('workspaces')
                ->cascadeOnDelete();

            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->cascadeOnDelete();

            $table->foreign('assignee_id')
                ->references('id')->on('team_members')
                ->nullOnDelete();

            $table->index(['workspace_id', 'status']);
            $table->index(['project_id', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

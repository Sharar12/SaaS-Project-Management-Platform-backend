<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('workspace_id', 36);
            $table->string('key', 10);
            $table->string('name', 160);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'planning', 'on_hold', 'completed'])->default('active');
            $table->string('color', 20)->default('#4F46E5');
            $table->date('start_date');
            $table->date('due_date');
            $table->string('lead_id', 36)->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();

            $table->foreign('workspace_id')
                ->references('id')->on('workspaces')
                ->cascadeOnDelete();

            $table->foreign('lead_id')
                ->references('id')->on('team_members')
                ->nullOnDelete();

            $table->unique(['workspace_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

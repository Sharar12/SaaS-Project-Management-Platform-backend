<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('workspace_id', 36);
            $table->string('actor_id', 36)->nullable();
            $table->string('action', 120);
            $table->string('target_title', 240);
            $table->timestamps();

            $table->foreign('workspace_id')
                ->references('id')->on('workspaces')
                ->cascadeOnDelete();

            $table->foreign('actor_id')
                ->references('id')->on('team_members')
                ->nullOnDelete();

            $table->index(['workspace_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

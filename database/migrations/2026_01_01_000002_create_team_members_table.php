<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('workspace_id', 36);
            $table->string('name', 120);
            $table->string('email', 160);
            $table->enum('role', ['owner', 'admin', 'member', 'viewer'])->default('member');
            $table->string('title', 120)->default('Contributor');
            $table->string('avatar_color', 50)->default('bg-indigo-600');
            $table->string('initials', 5);
            $table->enum('status', ['online', 'away', 'offline'])->default('online');
            $table->timestamps();

            $table->foreign('workspace_id')
                ->references('id')->on('workspaces')
                ->cascadeOnDelete();

            $table->unique(['workspace_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};

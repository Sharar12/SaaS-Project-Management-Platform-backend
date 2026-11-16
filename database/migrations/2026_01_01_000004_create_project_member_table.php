<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_member', function (Blueprint $table) {
            $table->string('project_id', 36);
            $table->string('team_member_id', 36);

            $table->primary(['project_id', 'team_member_id']);

            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->cascadeOnDelete();

            $table->foreign('team_member_id')
                ->references('id')->on('team_members')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_member');
    }
};

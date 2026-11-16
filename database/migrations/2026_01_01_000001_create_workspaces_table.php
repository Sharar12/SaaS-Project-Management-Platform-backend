<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('name', 120);
            $table->string('slug', 120)->unique();
            $table->string('industry', 120)->default('SaaS');
            $table->enum('plan', ['free', 'pro', 'enterprise'])->default('pro');
            $table->string('accent_color', 20)->default('#4F46E5');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspaces');
    }
};

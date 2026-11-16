<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class OrbitFlowDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Demo Users
        $u1 = User::firstOrCreate(
            ['email' => 'amina@orbittech.io'],
            ['name' => 'Amina Rahman', 'password' => 'password123']
        );
        $u2 = User::firstOrCreate(
            ['email' => 'liam@orbittech.io'],
            ['name' => 'Liam Carter', 'password' => 'password123']
        );
        $u3 = User::firstOrCreate(
            ['email' => 'elena@novastudio.co'],
            ['name' => 'Elena Rostova', 'password' => 'password123']
        );

        // 2. Workspaces
        Workspace::insertOrIgnore([
            [
                'id' => 'ws-1',
                'name' => 'OrbitTech Product',
                'slug' => 'orbittech-product',
                'industry' => 'SaaS Engineering',
                'plan' => 'pro',
                'accent_color' => '#4F46E5',
                'created_at' => now()->subDays(30),
                'updated_at' => now(),
            ],
            [
                'id' => 'ws-2',
                'name' => 'Nova Creative Studio',
                'slug' => 'nova-creative',
                'industry' => 'Design & Brand Agency',
                'plan' => 'enterprise',
                'accent_color' => '#0EA5E9',
                'created_at' => now()->subDays(20),
                'updated_at' => now(),
            ],
        ]);

        // 3. Team Members with User IDs linked
        TeamMember::insertOrIgnore([
            [
                'id' => 'mem-1',
                'workspace_id' => 'ws-1',
                'user_id' => $u1->id,
                'name' => 'Amina Rahman',
                'email' => 'amina@orbittech.io',
                'role' => 'owner',
                'title' => 'VP of Product',
                'avatar_color' => 'bg-indigo-600',
                'initials' => 'AR',
                'status' => 'online',
                'created_at' => now()->subDays(30),
                'updated_at' => now(),
            ],
            [
                'id' => 'mem-2',
                'workspace_id' => 'ws-1',
                'user_id' => $u2->id,
                'name' => 'Liam Carter',
                'email' => 'liam@orbittech.io',
                'role' => 'admin',
                'title' => 'Staff Frontend Architect',
                'avatar_color' => 'bg-sky-600',
                'initials' => 'LC',
                'status' => 'online',
                'created_at' => now()->subDays(28),
                'updated_at' => now(),
            ],
            [
                'id' => 'mem-3',
                'workspace_id' => 'ws-1',
                'user_id' => null,
                'name' => 'Sofia Chen',
                'email' => 'sofia@orbittech.io',
                'role' => 'member',
                'title' => 'Lead Laravel Engineer',
                'avatar_color' => 'bg-emerald-600',
                'initials' => 'SC',
                'status' => 'away',
                'created_at' => now()->subDays(25),
                'updated_at' => now(),
            ],
            [
                'id' => 'mem-4',
                'workspace_id' => 'ws-1',
                'user_id' => null,
                'name' => 'Marcus Vance',
                'email' => 'marcus@orbittech.io',
                'role' => 'member',
                'title' => 'Product Designer',
                'avatar_color' => 'bg-amber-600',
                'initials' => 'MV',
                'status' => 'online',
                'created_at' => now()->subDays(22),
                'updated_at' => now(),
            ],
            [
                'id' => 'mem-5',
                'workspace_id' => 'ws-2',
                'user_id' => $u3->id,
                'name' => 'Elena Rostova',
                'email' => 'elena@novastudio.co',
                'role' => 'owner',
                'title' => 'Creative Director',
                'avatar_color' => 'bg-rose-600',
                'initials' => 'ER',
                'status' => 'online',
                'created_at' => now()->subDays(20),
                'updated_at' => now(),
            ],
            [
                'id' => 'mem-6',
                'workspace_id' => 'ws-2',
                'user_id' => null,
                'name' => 'Daniel Kim',
                'email' => 'daniel@novastudio.co',
                'role' => 'member',
                'title' => 'Motion & UI Designer',
                'avatar_color' => 'bg-violet-600',
                'initials' => 'DK',
                'status' => 'offline',
                'created_at' => now()->subDays(18),
                'updated_at' => now(),
            ],
        ]);

        // Link existing members to users just in case
        TeamMember::where('email', 'amina@orbittech.io')->update(['user_id' => $u1->id]);
        TeamMember::where('email', 'liam@orbittech.io')->update(['user_id' => $u2->id]);
        TeamMember::where('email', 'elena@novastudio.co')->update(['user_id' => $u3->id]);
    }
}

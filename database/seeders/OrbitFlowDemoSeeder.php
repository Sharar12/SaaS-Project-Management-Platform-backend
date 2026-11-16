<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TeamMember;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class OrbitFlowDemoSeeder extends Seeder
{
    public function run(): void
    {
        Workspace::insert([
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

        TeamMember::insert([
            [
                'id' => 'mem-1',
                'workspace_id' => 'ws-1',
                'name' => 'Amina Rahman',
                'email' => 'amina@orbittech.io',
                'role' => 'owner',
                'title' => 'VP of Product',
                'avatar_color' => 'bg-indigo-600',
                'initials' => 'AR',
                'status' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'mem-2',
                'workspace_id' => 'ws-1',
                'name' => 'Liam Carter',
                'email' => 'liam@orbittech.io',
                'role' => 'admin',
                'title' => 'Staff Frontend Architect',
                'avatar_color' => 'bg-sky-600',
                'initials' => 'LC',
                'status' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'mem-3',
                'workspace_id' => 'ws-1',
                'name' => 'Sofia Chen',
                'email' => 'sofia@orbittech.io',
                'role' => 'member',
                'title' => 'Lead Laravel Engineer',
                'avatar_color' => 'bg-emerald-600',
                'initials' => 'SC',
                'status' => 'away',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'mem-4',
                'workspace_id' => 'ws-1',
                'name' => 'Marcus Vance',
                'email' => 'marcus@orbittech.io',
                'role' => 'member',
                'title' => 'Product Designer',
                'avatar_color' => 'bg-amber-600',
                'initials' => 'MV',
                'status' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'mem-5',
                'workspace_id' => 'ws-2',
                'name' => 'Elena Rostova',
                'email' => 'elena@novastudio.co',
                'role' => 'owner',
                'title' => 'Creative Director',
                'avatar_color' => 'bg-rose-600',
                'initials' => 'ER',
                'status' => 'online',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 'mem-6',
                'workspace_id' => 'ws-2',
                'name' => 'Daniel Kim',
                'email' => 'daniel@novastudio.co',
                'role' => 'member',
                'title' => 'Motion & UI Designer',
                'avatar_color' => 'bg-violet-600',
                'initials' => 'DK',
                'status' => 'offline',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $p1 = Project::create([
            'id' => 'proj-1',
            'workspace_id' => 'ws-1',
            'key' => 'CORE',
            'name' => 'Multi-Tenant Core v2.4',
            'description' => 'Unified workspace RBAC, webhook engine, and Laravel API gateway migration.',
            'status' => 'active',
            'color' => '#4F46E5',
            'start_date' => '2026-11-01',
            'due_date' => '2026-12-12',
            'lead_id' => 'mem-1',
            'tags' => ['Platform', 'API', 'Security'],
        ]);
        $p1->members()->sync(['mem-1', 'mem-2', 'mem-3', 'mem-4']);

        $p2 = Project::create([
            'id' => 'proj-2',
            'workspace_id' => 'ws-1',
            'key' => 'UIX',
            'name' => 'Design System & White Canvas UI',
            'description' => 'Decoupled interactive component kit featuring white surfaces and micro-animations.',
            'status' => 'active',
            'color' => '#0EA5E9',
            'start_date' => '2026-11-05',
            'due_date' => '2026-11-30',
            'lead_id' => 'mem-4',
            'tags' => ['UI/UX', 'Frontend', 'Next.js'],
        ]);
        $p2->members()->sync(['mem-2', 'mem-4']);

        $p3 = Project::create([
            'id' => 'proj-3',
            'workspace_id' => 'ws-1',
            'key' => 'BILL',
            'name' => 'Usage-Based SaaS Billing',
            'description' => 'Automated seat metering, subscription tiers, and downloadable PDF invoicing.',
            'status' => 'planning',
            'color' => '#10B981',
            'start_date' => '2026-11-18',
            'due_date' => '2026-12-28',
            'lead_id' => 'mem-3',
            'tags' => ['Billing', 'MySQL'],
        ]);
        $p3->members()->sync(['mem-1', 'mem-3']);

        $p4 = Project::create([
            'id' => 'proj-4',
            'workspace_id' => 'ws-2',
            'key' => 'NOVA',
            'name' => 'Q4 Global Brand Re-launch',
            'description' => 'Interactive campaign microsite, client portal deliverables, and asset library.',
            'status' => 'active',
            'color' => '#EC4899',
            'start_date' => '2026-11-02',
            'due_date' => '2026-12-08',
            'lead_id' => 'mem-5',
            'tags' => ['Brand', 'Campaign'],
        ]);
        $p4->members()->sync(['mem-5', 'mem-6']);

        Task::create([
            'id' => 'task-1',
            'workspace_id' => 'ws-1',
            'project_id' => 'proj-1',
            'code' => 'CORE-101',
            'title' => 'Design tenant isolation middleware & MySQL schema',
            'description' => 'Ensure every workspace query is scoped by workspace_id with indexed composite keys.',
            'status' => 'done',
            'priority' => 'urgent',
            'assignee_id' => 'mem-3',
            'start_date' => '2026-11-01',
            'due_date' => '2026-11-10',
            'tags' => ['Database', 'Laravel'],
            'estimated_hours' => 12,
        ]);

        Subtask::insert([
            ['id' => 'st-1', 'task_id' => 'task-1', 'title' => 'Draft ERD for workspaces & memberships', 'completed' => true, 'sort_order' => 0],
            ['id' => 'st-2', 'task_id' => 'task-1', 'title' => 'Add composite indexes for task status queries', 'completed' => true, 'sort_order' => 1],
        ]);

        TaskComment::create([
            'id' => 'cm-1',
            'task_id' => 'task-1',
            'author_id' => 'mem-1',
            'content' => 'Schema review approved. Ready for Laravel migration execution.',
        ]);

        Task::create([
            'id' => 'task-2',
            'workspace_id' => 'ws-1',
            'project_id' => 'proj-1',
            'code' => 'CORE-102',
            'title' => 'Implement Sanctum token & role permission guards',
            'description' => 'Support Owner, Admin, Member, and Viewer scopes across project endpoints.',
            'status' => 'in_progress',
            'priority' => 'high',
            'assignee_id' => 'mem-3',
            'start_date' => '2026-11-10',
            'due_date' => '2026-11-20',
            'tags' => ['Auth', 'Security'],
            'estimated_hours' => 10,
        ]);

        Task::create([
            'id' => 'task-3',
            'workspace_id' => 'ws-1',
            'project_id' => 'proj-2',
            'code' => 'UIX-204',
            'title' => 'Build animated Kanban drag-and-drop column transitions',
            'description' => 'Smooth white card elevation and optimistic state sync.',
            'status' => 'in_review',
            'priority' => 'high',
            'assignee_id' => 'mem-2',
            'start_date' => '2026-11-08',
            'due_date' => '2026-11-18',
            'tags' => ['Frontend', 'Motion'],
            'estimated_hours' => 8,
        ]);

        Task::create([
            'id' => 'task-4',
            'workspace_id' => 'ws-1',
            'project_id' => 'proj-3',
            'code' => 'BILL-301',
            'title' => 'Model subscription tiers & seat limit enforcement',
            'description' => 'Free, Pro, and Enterprise billing tiers.',
            'status' => 'todo',
            'priority' => 'medium',
            'assignee_id' => 'mem-1',
            'start_date' => '2026-11-16',
            'due_date' => '2026-11-28',
            'tags' => ['SaaS', 'Billing'],
            'estimated_hours' => 9,
        ]);

        Task::create([
            'id' => 'task-5',
            'workspace_id' => 'ws-2',
            'project_id' => 'proj-4',
            'code' => 'NOVA-12',
            'title' => 'Finalize interactive hero motion storyboard',
            'description' => 'Prepare crisp white-surface micro-interactions for Q4 launch.',
            'status' => 'in_progress',
            'priority' => 'high',
            'assignee_id' => 'mem-6',
            'start_date' => '2026-11-10',
            'due_date' => '2026-11-21',
            'tags' => ['Motion', 'Brand'],
            'estimated_hours' => 11,
        ]);

        ActivityLog::insert([
            [
                'id' => 'act-1',
                'workspace_id' => 'ws-1',
                'actor_id' => 'mem-3',
                'action' => 'completed',
                'target_title' => 'CORE-101 Design tenant isolation middleware & MySQL schema',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'id' => 'act-2',
                'workspace_id' => 'ws-1',
                'actor_id' => 'mem-2',
                'action' => 'requested review on',
                'target_title' => 'UIX-204 Build animated Kanban drag-and-drop column transitions',
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ],
        ]);
    }
}

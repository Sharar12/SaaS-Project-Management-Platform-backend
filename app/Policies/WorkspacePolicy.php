<?php

namespace App\Policies;

use App\Models\TeamMember;
use App\Models\User;
use App\Models\Workspace;

class WorkspacePolicy
{
    private function roleFor(User $user, Workspace $workspace): ?string
    {
        return TeamMember::where('workspace_id', $workspace->id)
            ->where('user_id', $user->id)
            ->value('role');
    }

    public function view(User $user, Workspace $workspace): bool
    {
        return $this->roleFor($user, $workspace) !== null;
    }

    public function manage(User $user, Workspace $workspace): bool
    {
        return in_array($this->roleFor($user, $workspace), ['owner', 'admin'], true);
    }

    public function writeWork(User $user, Workspace $workspace): bool
    {
        return in_array($this->roleFor($user, $workspace), ['owner', 'admin', 'member'], true);
    }
}

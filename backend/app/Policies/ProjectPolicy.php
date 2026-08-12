<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    // Single-admin model: any authenticated user may manage any project.
    // If multi-admin roles are added later, tighten these to check role.

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Project $project): bool
    {
        return true;
    }

    public function delete(User $user, Project $project): bool
    {
        return true;
    }

    public function restore(User $user, Project $project): bool
    {
        return true;
    }
}

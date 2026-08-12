<?php

namespace App\Policies;

use App\Models${name};
use App\Models\User;

class SkillPolicy
{
    // Single-admin model: any authenticated user may manage any record.

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Skill $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Skill $model): bool
    {
        return true;
    }

    public function delete(User $user, Skill $model): bool
    {
        return true;
    }
}

<?php

namespace App\Policies;

use App\Models${name};
use App\Models\User;

class ExperiencePolicy
{
    // Single-admin model: any authenticated user may manage any record.

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Experience $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Experience $model): bool
    {
        return true;
    }

    public function delete(User $user, Experience $model): bool
    {
        return true;
    }
}

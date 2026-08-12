<?php

namespace App\Policies;

use App\Models${name};
use App\Models\User;

class EducationPolicy
{
    // Single-admin model: any authenticated user may manage any record.

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Education $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Education $model): bool
    {
        return true;
    }

    public function delete(User $user, Education $model): bool
    {
        return true;
    }
}

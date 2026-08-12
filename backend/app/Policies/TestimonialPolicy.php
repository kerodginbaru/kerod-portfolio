<?php

namespace App\Policies;

use App\Models${name};
use App\Models\User;

class TestimonialPolicy
{
    // Single-admin model: any authenticated user may manage any record.

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Testimonial $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Testimonial $model): bool
    {
        return true;
    }

    public function delete(User $user, Testimonial $model): bool
    {
        return true;
    }
}

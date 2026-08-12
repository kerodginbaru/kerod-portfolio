<?php

use Illuminate\Support\Facades\Route;

// This backend is a pure JSON API consumed by the Next.js frontend — no
// server-rendered routes live here. Kept intentionally empty rather than
// removed, since Laravel's default web middleware group (sessions,
// CSRF) still needs a routes/web.php to exist for local tooling like
// Sanctum's stateful auth and the storage:link helper page during dev.

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'data' => ['name' => config('app.name')],
        'message' => 'Kerod Ginbaru portfolio API. See /api for endpoints.',
    ]);
});

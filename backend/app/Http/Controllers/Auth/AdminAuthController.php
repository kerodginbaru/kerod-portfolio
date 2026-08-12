<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    use ApiResponse;

    /**
     * Issue a Sanctum token for the admin. There is no public registration
     * endpoint — admin accounts are created via `php artisan admin:create`
     * or a seeder, never through the API.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->string('email'))->first();

        if (! $user || ! Hash::check($request->string('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        // One active token per device/session; name it for easy auditing
        // and revocation from the admin "sessions" screen if you add one.
        $token = $user->createToken(
            'admin-'.now()->timestamp,
            ['admin']
        )->plainTextToken;

        return $this->success([
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
            'token' => $token,
        ], 'Logged in successfully.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully.');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ], 'Profile retrieved successfully.');
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update(['password' => Hash::make($request->string('password'))]);

        // Revoke every other token so old sessions can't keep using the
        // previous password's token set.
        $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();

        return $this->success(null, 'Password updated successfully.');
    }
}

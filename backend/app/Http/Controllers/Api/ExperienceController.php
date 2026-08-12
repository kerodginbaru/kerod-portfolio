<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Http\JsonResponse;

class ExperienceController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $experience = Experience::query()
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        return $this->success(ExperienceResource::collection($experience), 'Experience retrieved successfully.');
    }
}

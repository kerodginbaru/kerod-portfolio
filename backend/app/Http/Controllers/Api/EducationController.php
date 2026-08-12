<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\EducationResource;
use App\Models\Education;
use Illuminate\Http\JsonResponse;

class EducationController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $education = Education::query()->orderByDesc('start_date')->get();

        return $this->success(EducationResource::collection($education), 'Education retrieved successfully.');
    }
}

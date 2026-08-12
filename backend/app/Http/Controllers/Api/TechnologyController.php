<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TechnologyResource;
use App\Models\Technology;
use Illuminate\Http\JsonResponse;

class TechnologyController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $technologies = Technology::query()->orderBy('name')->get();

        return $this->success(TechnologyResource::collection($technologies), 'Technologies retrieved successfully.');
    }
}

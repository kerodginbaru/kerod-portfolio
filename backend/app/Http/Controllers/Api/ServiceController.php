<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $services = Service::query()->orderBy('sort_order')->get();

        return $this->success(ServiceResource::collection($services), 'Services retrieved successfully.');
    }
}

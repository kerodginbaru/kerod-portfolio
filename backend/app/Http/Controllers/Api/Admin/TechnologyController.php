<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTechnologyRequest;
use App\Http\Requests\Admin\UpdateTechnologyRequest;
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

    public function store(StoreTechnologyRequest $request): JsonResponse
    {
        $technology = Technology::create($request->validated());

        return $this->success(new TechnologyResource($technology), 'Technology created successfully.', 201);
    }

    public function update(UpdateTechnologyRequest $request, Technology $technology): JsonResponse
    {
        $technology->update($request->validated());

        return $this->success(new TechnologyResource($technology), 'Technology updated successfully.');
    }

    public function destroy(Technology $technology): JsonResponse
    {
        $technology->delete();

        return $this->success(null, 'Technology deleted successfully.');
    }
}

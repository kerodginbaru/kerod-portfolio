<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExperienceRequest;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Http\JsonResponse;

class ExperienceController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $experience = Experience::query()->orderByDesc('start_date')->get();

        return $this->success(ExperienceResource::collection($experience), 'Experience retrieved successfully.');
    }

    public function store(StoreExperienceRequest $request): JsonResponse
    {
        $this->authorize('create', Experience::class);

        $experience = Experience::create($request->validated());

        return $this->success(new ExperienceResource($experience), 'Experience entry created successfully.', 201);
    }

    public function update(StoreExperienceRequest $request, Experience $experience): JsonResponse
    {
        $this->authorize('update', $experience);

        $experience->update($request->validated());

        return $this->success(new ExperienceResource($experience), 'Experience entry updated successfully.');
    }

    public function destroy(Experience $experience): JsonResponse
    {
        $this->authorize('delete', $experience);

        $experience->delete();

        return $this->success(null, 'Experience entry deleted successfully.');
    }
}

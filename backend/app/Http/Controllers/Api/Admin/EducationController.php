<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEducationRequest;
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

    public function store(StoreEducationRequest $request): JsonResponse
    {
        $this->authorize('create', Education::class);

        $education = Education::create($request->validated());

        return $this->success(new EducationResource($education), 'Education entry created successfully.', 201);
    }

    public function update(StoreEducationRequest $request, Education $education): JsonResponse
    {
        $this->authorize('update', $education);

        $education->update($request->validated());

        return $this->success(new EducationResource($education), 'Education entry updated successfully.');
    }

    public function destroy(Education $education): JsonResponse
    {
        $this->authorize('delete', $education);

        $education->delete();

        return $this->success(null, 'Education entry deleted successfully.');
    }
}

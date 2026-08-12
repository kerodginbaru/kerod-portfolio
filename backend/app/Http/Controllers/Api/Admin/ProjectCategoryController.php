<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectCategoryRequest;
use App\Http\Requests\Admin\UpdateProjectCategoryRequest;
use App\Http\Resources\ProjectCategoryResource;
use App\Models\ProjectCategory;
use Illuminate\Http\JsonResponse;

class ProjectCategoryController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $categories = ProjectCategory::query()->orderBy('sort_order')->get();

        return $this->success(ProjectCategoryResource::collection($categories), 'Categories retrieved successfully.');
    }

    public function store(StoreProjectCategoryRequest $request): JsonResponse
    {
        $category = ProjectCategory::create($request->validated());

        return $this->success(new ProjectCategoryResource($category), 'Category created successfully.', 201);
    }

    public function update(UpdateProjectCategoryRequest $request, ProjectCategory $projectCategory): JsonResponse
    {
        $projectCategory->update($request->validated());

        return $this->success(new ProjectCategoryResource($projectCategory), 'Category updated successfully.');
    }

    public function destroy(ProjectCategory $projectCategory): JsonResponse
    {
        if ($projectCategory->projects()->exists()) {
            return $this->error('Cannot delete a category that still has projects assigned to it.', 422);
        }

        $projectCategory->delete();

        return $this->success(null, 'Category deleted successfully.');
    }
}

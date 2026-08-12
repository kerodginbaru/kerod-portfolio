<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSkillCategoryRequest;
use App\Http\Resources\SkillCategoryResource;
use App\Models\SkillCategory;
use Illuminate\Http\JsonResponse;

class SkillCategoryController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $categories = SkillCategory::query()->with('skills')->orderBy('sort_order')->get();

        return $this->success(SkillCategoryResource::collection($categories), 'Skill categories retrieved successfully.');
    }

    public function store(StoreSkillCategoryRequest $request): JsonResponse
    {
        $category = SkillCategory::create($request->validated());

        return $this->success(new SkillCategoryResource($category->load('skills')), 'Skill category created successfully.', 201);
    }

    public function destroy(SkillCategory $skillCategory): JsonResponse
    {
        if ($skillCategory->skills()->exists()) {
            return $this->error('Cannot delete a category that still has skills assigned to it.', 422);
        }

        $skillCategory->delete();

        return $this->success(null, 'Skill category deleted successfully.');
    }
}

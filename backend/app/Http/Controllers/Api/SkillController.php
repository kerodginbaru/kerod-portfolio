<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\SkillCategoryResource;
use App\Models\SkillCategory;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $categories = SkillCategory::query()
            ->with('skills')
            ->orderBy('sort_order')
            ->get();

        return $this->success(SkillCategoryResource::collection($categories), 'Skills retrieved successfully.');
    }
}

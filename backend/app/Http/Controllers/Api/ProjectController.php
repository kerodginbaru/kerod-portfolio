<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Project::query()
            ->published()
            ->with(['category', 'technologies', 'images'])
            ->orderBy('sort_order')
            ->orderByDesc('year');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($category = $request->query('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        if ($technology = $request->query('technology')) {
            $query->whereHas('technologies', fn ($q) => $q->where('slug', $technology));
        }

        $projects = $query->paginate($request->integer('per_page', 24));

        return $this->success(ProjectResource::collection($projects), 'Projects retrieved successfully.');
    }

    public function featured(): JsonResponse
    {
        $projects = Project::query()
            ->published()
            ->featured()
            ->with(['category', 'technologies', 'images'])
            ->orderBy('sort_order')
            ->get();

        return $this->success(ProjectResource::collection($projects), 'Featured projects retrieved successfully.');
    }

    public function show(Project $project): JsonResponse
    {
        if ($project->status === 'archived') {
            return $this->error('Project not found.', 404);
        }

        $project->load(['category', 'technologies', 'images']);

        return $this->success(new ProjectResource($project), 'Project retrieved successfully.');
    }
}

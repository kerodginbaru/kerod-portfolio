<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Project::class);

        $query = Project::query()->with(['category', 'technologies', 'images'])->orderBy('sort_order');

        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        $projects = $query->paginate($request->integer('per_page', 20));

        return $this->success(ProjectResource::collection($projects), 'Projects retrieved successfully.');
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $technologyIds = $data['technology_ids'] ?? [];
        unset($data['technology_ids']);

        $project = Project::create($data);
        $project->technologies()->sync($technologyIds);
        $project->load(['category', 'technologies', 'images']);

        return $this->success(new ProjectResource($project), 'Project created successfully.', 201);
    }

    public function show(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $project->load(['category', 'technologies', 'images']);

        return $this->success(new ProjectResource($project), 'Project retrieved successfully.');
    }

    public function update(UpdateProjectRequest $request, Project $project): JsonResponse
    {
        $data = $request->validated();
        $technologyIds = $data['technology_ids'] ?? null;
        unset($data['technology_ids']);

        $project->update($data);

        if ($technologyIds !== null) {
            $project->technologies()->sync($technologyIds);
        }

        $project->load(['category', 'technologies', 'images']);

        return $this->success(new ProjectResource($project), 'Project updated successfully.');
    }

    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $project->delete(); // soft delete

        return $this->success(null, 'Project deleted successfully.');
    }

    public function restore(int $id): JsonResponse
    {
        $project = Project::withTrashed()->findOrFail($id);
        $this->authorize('restore', $project);

        $project->restore();

        return $this->success(new ProjectResource($project->fresh(['category', 'technologies', 'images'])), 'Project restored successfully.');
    }

    public function toggleFeatured(Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $project->update(['featured' => ! $project->featured]);

        return $this->success(new ProjectResource($project), 'Project updated successfully.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Project::class);

        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:projects,id'],
        ]);

        foreach ($request->array('order') as $index => $id) {
            Project::whereKey($id)->update(['sort_order' => $index]);
        }

        return $this->success(null, 'Project order updated successfully.');
    }
}

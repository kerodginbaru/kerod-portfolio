<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSkillRequest;
use App\Http\Requests\Admin\UpdateSkillRequest;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    use ApiResponse;

    public function store(StoreSkillRequest $request): JsonResponse
    {
        $this->authorize('create', Skill::class);

        $skill = Skill::create($request->validated());

        return $this->success(new SkillResource($skill), 'Skill created successfully.', 201);
    }

    public function update(UpdateSkillRequest $request, Skill $skill): JsonResponse
    {
        $this->authorize('update', $skill);

        $skill->update($request->validated());

        return $this->success(new SkillResource($skill), 'Skill updated successfully.');
    }

    public function destroy(Skill $skill): JsonResponse
    {
        $this->authorize('delete', $skill);

        $skill->delete();

        return $this->success(null, 'Skill deleted successfully.');
    }
}

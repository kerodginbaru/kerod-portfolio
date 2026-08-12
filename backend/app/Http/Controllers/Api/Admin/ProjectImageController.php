<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectImageRequest;
use App\Http\Resources\ProjectImageResource;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectImageController extends Controller
{
    use ApiResponse;

    public function store(StoreProjectImageRequest $request, Project $project): JsonResponse
    {
        $file = $request->file('image');

        // Never trust the client-provided filename. Generate a random,
        // collision-resistant name and keep only a safe extension derived
        // from the validated MIME type.
        $extension = $file->extension() ?: 'jpg';
        $safeName = Str::uuid().'.'.$extension;
        $path = $file->storeAs("projects/{$project->id}", $safeName, 'public');

        $isCover = $request->boolean('is_cover');
        if ($isCover) {
            $project->images()->update(['is_cover' => false]);
        }

        $image = $project->images()->create([
            'image_path' => $path,
            'alt_text' => $request->string('alt_text') ?: null,
            'caption' => $request->string('caption') ?: null,
            'is_cover' => $isCover,
            'sort_order' => $project->images()->max('sort_order') + 1,
        ]);

        return $this->success(new ProjectImageResource($image), 'Image uploaded successfully.', 201);
    }

    public function destroy(Project $project, ProjectImage $image): JsonResponse
    {
        $this->authorize('update', $project);
        abort_unless($image->project_id === $project->id, 404);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return $this->success(null, 'Image deleted successfully.');
    }

    public function setCover(Project $project, ProjectImage $image): JsonResponse
    {
        $this->authorize('update', $project);
        abort_unless($image->project_id === $project->id, 404);

        $project->images()->update(['is_cover' => false]);
        $image->update(['is_cover' => true]);

        return $this->success(new ProjectImageResource($image), 'Cover image updated successfully.');
    }

    public function reorder(Request $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:project_images,id'],
        ]);

        foreach ($request->array('order') as $index => $id) {
            ProjectImage::where('id', $id)->where('project_id', $project->id)->update(['sort_order' => $index]);
        }

        return $this->success(null, 'Image order updated successfully.');
    }
}

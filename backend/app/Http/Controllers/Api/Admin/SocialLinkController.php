<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSocialLinkRequest;
use App\Http\Resources\SocialLinkResource;
use App\Models\SocialLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $links = SocialLink::query()->orderBy('sort_order')->get();

        return $this->success(SocialLinkResource::collection($links), 'Social links retrieved successfully.');
    }

    public function store(StoreSocialLinkRequest $request): JsonResponse
    {
        $this->authorize('create', SocialLink::class);

        $link = SocialLink::create($request->validated());

        return $this->success(new SocialLinkResource($link), 'Social link created successfully.', 201);
    }

    public function update(StoreSocialLinkRequest $request, SocialLink $socialLink): JsonResponse
    {
        $this->authorize('update', $socialLink);

        $socialLink->update($request->validated());

        return $this->success(new SocialLinkResource($socialLink), 'Social link updated successfully.');
    }

    public function destroy(SocialLink $socialLink): JsonResponse
    {
        $this->authorize('delete', $socialLink);

        $socialLink->delete();

        return $this->success(null, 'Social link deleted successfully.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:social_links,id'],
        ]);

        foreach ($request->array('order') as $index => $id) {
            SocialLink::whereKey($id)->update(['sort_order' => $index]);
        }

        return $this->success(null, 'Social link order updated successfully.');
    }
}

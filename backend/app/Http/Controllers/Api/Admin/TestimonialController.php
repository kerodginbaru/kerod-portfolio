<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestimonialRequest;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Testimonial::class);

        $testimonials = Testimonial::query()->orderBy('sort_order')->get();

        return $this->success(TestimonialResource::collection($testimonials), 'Testimonials retrieved successfully.');
    }

    public function store(StoreTestimonialRequest $request): JsonResponse
    {
        $this->authorize('create', Testimonial::class);

        $testimonial = Testimonial::create($request->validated());

        return $this->success(new TestimonialResource($testimonial), 'Testimonial created successfully.', 201);
    }

    public function update(StoreTestimonialRequest $request, Testimonial $testimonial): JsonResponse
    {
        $this->authorize('update', $testimonial);

        $testimonial->update($request->validated());

        return $this->success(new TestimonialResource($testimonial), 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $this->authorize('delete', $testimonial);

        $testimonial->delete();

        return $this->success(null, 'Testimonial deleted successfully.');
    }
}

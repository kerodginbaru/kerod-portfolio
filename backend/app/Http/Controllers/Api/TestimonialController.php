<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $testimonials = Testimonial::query()
            ->published()
            ->orderBy('sort_order')
            ->get();

        return $this->success(TestimonialResource::collection($testimonials), 'Testimonials retrieved successfully.');
    }
}

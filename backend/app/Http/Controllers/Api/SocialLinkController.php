<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\SocialLinkResource;
use App\Models\SocialLink;
use Illuminate\Http\JsonResponse;

class SocialLinkController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $links = SocialLink::query()->enabled()->orderBy('sort_order')->get();

        return $this->success(SocialLinkResource::collection($links), 'Social links retrieved successfully.');
    }
}

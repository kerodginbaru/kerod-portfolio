<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;

class SiteSettingController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        $settings = SiteSetting::allAsArray();

        return $this->success(new SiteSettingResource($settings), 'Site settings retrieved successfully.');
    }
}

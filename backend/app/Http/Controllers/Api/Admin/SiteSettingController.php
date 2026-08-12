<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingRequest;
use App\Http\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;

class SiteSettingController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        return $this->success(new SiteSettingResource(SiteSetting::allAsArray()), 'Site settings retrieved successfully.');
    }

    public function update(UpdateSiteSettingRequest $request): JsonResponse
    {
        foreach ($request->validated('settings') as $key => $value) {
            SiteSetting::set($key, $value);
        }

        return $this->success(new SiteSettingResource(SiteSetting::allAsArray()), 'Site settings updated successfully.');
    }
}

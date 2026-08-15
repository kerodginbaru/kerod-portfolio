<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingRequest;
use App\Http\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    /**
     * Upload/replace the profile photo shown in the site hero and admin
     * panel. Same safety rules as project image uploads: validated
     * MIME/extension/size, server-generated filename, old file removed.
     */
    public function uploadPhoto(Request $request): JsonResponse
    {
        $request->validate([
            'photo' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $old = SiteSetting::get('profile_photo');
        if ($old) {
            Storage::disk('public')->delete($old);
        }

        $file = $request->file('photo');
        $extension = $file->extension() ?: 'jpg';
        $path = $file->storeAs('settings', Str::uuid().'.'.$extension, 'public');

        SiteSetting::set('profile_photo', $path);

        return $this->success(new SiteSettingResource(SiteSetting::allAsArray()), 'Profile photo updated successfully.');
    }
}

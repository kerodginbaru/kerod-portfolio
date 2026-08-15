<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * Wraps a plain associative array (App\Models\SiteSetting::allAsArray())
 * rather than a single Eloquent model, since settings are stored as
 * key/value rows but consumed by the frontend as one flat object.
 */
class SiteSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $s = $this->resource;

        return [
            'name' => $s['name'] ?? 'Kerod Ginbaru',
            'professional_title' => $s['professional_title'] ?? 'Full-Stack & Mobile Developer',
            'email' => $s['email'] ?? 'kerodhope@gmail.com',
            'phone' => $s['phone'] ?? '0965201930',
            'location' => $s['location'] ?? 'Ethiopia',
            'hero_heading' => $s['hero_heading'] ?? 'I build software that solves real business problems.',
            'hero_description' => $s['hero_description'] ?? '',
            'about_text' => $s['about_text'] ?? '',
            'contact_cta' => $s['contact_cta'] ?? '',
            'resume_url' => $s['resume_url'] ?? null,
            'profile_photo_url' => isset($s['profile_photo']) && $s['profile_photo']
                ? Storage::disk('public')->url($s['profile_photo'])
                : null,
        ];
    }
}

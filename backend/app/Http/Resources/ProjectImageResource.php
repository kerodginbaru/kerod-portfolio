<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'url' => $this->url, // accessor on the model, never image_path directly
            'alt_text' => $this->alt_text,
            'caption' => $this->caption,
            'is_cover' => (bool) $this->is_cover,
            'sort_order' => $this->sort_order,
        ];
    }
}

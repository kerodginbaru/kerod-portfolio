<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $cover = $this->images->firstWhere('is_cover', true) ?? $this->images->first();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'problem' => $this->problem,
            'solution' => $this->solution,
            'category' => new ProjectCategoryResource($this->whenLoaded('category')),
            'status' => $this->status,
            'featured' => (bool) $this->featured,
            'year' => $this->year,
            'github_url' => $this->github_url,
            'live_url' => $this->live_url,
            'architecture' => $this->architecture,
            'challenges' => $this->challenges,
            'lessons_learned' => $this->lessons_learned,
            'technologies' => TechnologyResource::collection($this->whenLoaded('technologies')),
            'images' => ProjectImageResource::collection($this->whenLoaded('images')),
            'cover_image' => $cover?->url,
        ];
    }
}

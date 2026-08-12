<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'image_path', 'alt_text', 'caption', 'sort_order', 'is_cover'];

    protected function casts(): array
    {
        return ['is_cover' => 'boolean'];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Public, CDN/storage-relative URL for this image. Never returns a raw
     * server filesystem path — that stays internal to image_path.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->image_path);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'problem',
        'solution',
        'category_id',
        'status',
        'featured',
        'year',
        'github_url',
        'live_url',
        'architecture',
        'challenges',
        'lessons_learned',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'year' => 'integer',
        ];
    }

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'project_technology');
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function coverImage()
    {
        return $this->hasOne(ProjectImage::class)->where('is_cover', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', '!=', 'archived');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

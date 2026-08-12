<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'role', 'company', 'message', 'image', 'featured', 'published', 'sort_order'];

    protected function casts(): array
    {
        return ['featured' => 'boolean', 'published' => 'boolean'];
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}

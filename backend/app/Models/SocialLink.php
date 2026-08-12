<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = ['platform', 'url', 'icon', 'enabled', 'sort_order'];

    protected function casts(): array
    {
        return ['enabled' => 'boolean'];
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }
}

<?php

namespace App\Domain\Press\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'website',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}

<?php

namespace App\Domain\Press\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $fillable = [
        'source_id',
        'title',
        'author',
        'category',
        'content',
        'published_at',
        'url',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
}

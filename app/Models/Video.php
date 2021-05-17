<?php

namespace App\Models;

use App\Services\VideoService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['video_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query): Builder
    {
        return $query->where('is_published', 1);
    }

    public function scopeUnPublished($query): Builder
    {
        return $query->where('is_published', 0);
    }

    public function getVideoIdAttribute()
    {
        $videoService = app()->make(VideoService::class);
        return $videoService->youtubeThumbnail($this->url);
    }
}

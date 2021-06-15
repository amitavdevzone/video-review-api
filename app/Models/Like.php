<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity',
        'entity_id',
        'user_id',
    ];

    protected static function booted()
    {
        static::created(function (Like $like) {
            if ($like->entity === 'video') {
                $video = Video::find($like->entity_id);
                $video->like_count++;
                $video->save();
            }
        });

        static::deleted(function (Like $like) {
            if ($like->entity === 'video') {
                $video = Video::find($like->entity_id);
                $video->like_count--;
                $video->save();
            }
        });
    }
}

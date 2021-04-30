<?php

namespace App\Rules;

use App\Services\VideoService;
use Illuminate\Contracts\Validation\Rule;

class YoutubeUrlRule implements Rule
{
    public function passes($attribute, $value)
    {
        $videoService = app()->make(VideoService::class);

        return $videoService->validateYoutubeUrl($value);
    }

    public function message()
    {
        return 'The validation error message.';
    }
}

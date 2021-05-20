@component('mail::message')
# A new video has been submitted for review.

{{$video->title}}

<img src="https://img.youtube.com/vi/{{$video->video_id}}/mqdefault.jpg" alt="$video->title">

@component('mail::button', ['url' => 'https://video-reviews.vercel.app'])
Login and view
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

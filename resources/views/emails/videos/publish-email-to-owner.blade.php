@component('mail::message')
    # Your video has been published

    Your video is published now.

    @component('mail::button', ['url' => ''])
        Button Text
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent

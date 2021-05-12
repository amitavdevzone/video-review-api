@component('mail::message')
# Verify your email address at Video Review

Hi {{$user->name}},

I am very happy to have you on-board. As last step, I would request you to validate your email address.

You just need to click on the <strong>Verify</strong> button below and we are done.

@component('mail::button', ['url' => $url])
Verify
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

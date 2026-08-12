@component('mail::message')
# New message from the portfolio site

**From:** {{ $contactMessage->name }} ({{ $contactMessage->email }})
@if($contactMessage->phone)
**Phone:** {{ $contactMessage->phone }}
@endif
**Subject:** {{ $contactMessage->subject }}

{{ $contactMessage->message }}

@component('mail::button', ['url' => config('app.frontend_url', env('FRONTEND_URL')).'/admin/messages'])
Open in admin
@endcomponent

Sent from the contact form at {{ config('app.url') }}.
@endcomponent

@component('mail::message')
# New Contact Us Message

You have received a new message from your website's contact form.

**Email:** {{ $email }}

**Message:**  
{{ $message }}

@component('mail::button', ['url' => 'mailto:' . $email])
Reply to {{ $email }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

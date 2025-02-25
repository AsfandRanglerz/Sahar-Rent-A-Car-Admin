@component('mail::message')
# Welcome, {{ $name }}!

Thank you for registering with us. Your account has been successfully created.

## Your Details:
- **Name:** {{ $name }}
- **Email:** {{ $email }}
- **Phone:** {{ $phone }}

{{-- @component('mail::button', ['url' => url('/')])
Visit Website
@endcomponent --}}

If you have any questions, feel free to contact us.

Thanks,  
{{ config('app.name') }}
@endcomponent

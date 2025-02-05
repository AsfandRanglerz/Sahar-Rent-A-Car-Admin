@component('mail::message')
# Welcome {{ $name }}!

Your account has been successfully created. Below are your login details:

**Email:** {{ $email }}
**Phone:** {{ $phone }}
**Password:** {{ $password }}

Please make sure to change your password after logging in to keep your account secure.

Thanks,
{{ config('app.name') }}
@endcomponent

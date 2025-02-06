@component('mail::message')
# Password Reset OTP

Use the OTP below to reset your password:

**{{ $otp }}**

This OTP will expire in 5 minutes.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

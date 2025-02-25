@component('mail::message')
# Password Reset OTP

Use the OTP below to reset your password:

**{{ $otp }}**

If you did not request for OTP, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

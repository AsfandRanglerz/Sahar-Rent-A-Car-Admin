@component('mail::message')
# Your OTP Code

Hello,

Your One-Time Password (OTP) for login is:

# **{{ $otp }}**

This OTP is valid for 5 minutes. Do not share it with anyone.


If you did not request for OTP, please ignore this email.

Thanks,  
{{ config('app.name') }}
@endcomponent

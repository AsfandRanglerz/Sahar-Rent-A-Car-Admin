{{-- @component('mail::message')
# Your OTP Code

Hello,

Your One-Time Password (OTP) for login is:

# **{{ $otp }}**

Do not share it with anyone.


If you did not request for OTP, please ignore this email.

Thanks,  
{{ config('app.name') }}
@endcomponent --}}

<!DOCTYPE html>
<html>
<head>
    <title>Your OTP Code</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2>Your OTP Code</h2>
    </div>

    <p>Hello,</p>

    <p>Your One-Time Password (OTP) for login is:</p>

    <div style="text-align: center; margin: 20px 0;">
        <span style="display: inline-block; font-size: 24px; font-weight: bold; background-color: #f3f3f3; padding: 10px 20px; border-radius: 5px;">
            {{ $otp }}
        </span>
    </div>

    <p>Please do not share this code with anyone.</p>

    <p>If you did not request an OTP, please ignore this email.</p>

    <p>Thanks,<br>
    <strong>{{ config('app.name') }}</strong></p>
</body>
</html>

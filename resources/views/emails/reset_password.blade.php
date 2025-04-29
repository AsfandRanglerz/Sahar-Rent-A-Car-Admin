{{-- @component('mail::message')

<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="{{ config('app.name') }} Logo"
        style="height: 100px; margin-bottom: 20px;"> --}}
    {{-- <h3><strong>Welcome to <span>Sahar Rent a Car</span></strong></h3> --}}
{{-- </div>
    We have received reset password request, please click below button to reset password.
@component('mail::button', ['url' => $detail['url']])
Reset Password
@endcomponent

Thanks,
**Sahar Rent A Car**
@endcomponent --}}

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password Request</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="text-align: center; margin-bottom: 30px;">
        <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" 
             alt="{{ config('app.name') }} Logo" 
             style="height: 100px; margin-bottom: 20px;">
    </div>

    <p>We have received a request to reset your password. Please click the button below to reset your password:</p>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $detail['url'] }}" 
           style="display: inline-block; padding: 10px 20px; background-color: #021642; color: #fff; text-decoration: none; border-radius: 5px;">
            Reset Password
        </a>
    </div>

    <p>If you did not request a password reset, please ignore this email.</p>

    <p>Thanks,<br>
    <strong>Sahar Rent A Car</strong></p>
</body>
</html>

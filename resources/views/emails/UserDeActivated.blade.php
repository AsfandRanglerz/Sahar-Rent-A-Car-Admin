{{-- @component('mail::message') --}}
{{-- <p style="margin:0 auto 10px;width:145px">Store Manager Deactivated</p> --}}
{{-- <div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/images/logo.png') }}" alt="Logo" style="max-width: 100px;">
</div> --}}
{{-- <div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="{{ config('app.name') }} Logo"
        style="height: 100px; margin-bottom: 20px;"> --}}
    {{-- <h3><strong>Welcome to <span>Sahar Rent a Car</span></strong></h3> --}}
{{-- </div>
<h1>Hi {{ $message['name'] }},</h1>
<p>We regret to inform you that your account has been deactivated by the Saher Rent A Car team.
<h1>Reason:</h1>
<p>{{ $message['reason'] }}</p>


Thanks,
**Sahar Rent A Car** --}}

{{-- {{ config('app.name') }} --}}
{{-- @endcomponent --}}

<!DOCTYPE html>
<html>
<head>
    <title>Customer Deactivation Email</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="text-align: center; margin-bottom: 30px;">
        <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="{{ config('app.name') }} Logo" style="height: 100px;">
    </div>

    <h2>Hi {{ $message['name'] }},</h2>

    <p style="font-size: 16px;">
        We regret to inform you that your account has been <strong>Deactivated</strong> by the <strong>Sahar Rent A Car</strong> team.
    </p>

    <h3>Reason:</h3>
    <p>{{ $message['reason'] }}</p>

    <p>
        Thanks,<br>
        <strong>Sahar Rent A Car</strong>
    </p>
</body>
</html>

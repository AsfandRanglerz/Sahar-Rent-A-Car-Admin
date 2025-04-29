{{-- @component('mail::message') --}}
{{-- <div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/images/logo.png') }}" alt="Logo" style="max-width: 100px;">
</div> --}}

{{-- <div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="{{ config('app.name') }} Logo"
        style="height: 100px; margin-bottom: 20px;"> --}}
    {{-- <h3><strong>Welcome to <span>Sahar Rent a Car</span></strong></h3> --}}
{{-- </div>
<h1>Hi {{ $message['name'] }},</h1>
<p>Congratulations! Your License has been approved by Saher Rent A Car Team.</p>

Thanks,
**Sahar Rent A Car** --}}
{{-- {{ config('app.name') }} --}}
{{-- @endcomponent --}}

<!DOCTYPE html>
<html>
<head>
    <title>License Approval - Sahar Rent A Car</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="text-align: center; margin-bottom: 30px;">
        <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" 
             alt="{{ config('app.name') }} Logo" 
             style="height: 100px; margin-bottom: 20px;">
    </div>

    <h2>Hi {{ $message['name'] }},</h2>

    <p>
        Congratulations! Your license has been <strong>approved</strong> by the <strong>Sahar Rent A Car</strong> team.
    </p>


    <p>
        Thanks,<br>
        <strong>Sahar Rent A Car</strong>
    </p>
</body>
</html>

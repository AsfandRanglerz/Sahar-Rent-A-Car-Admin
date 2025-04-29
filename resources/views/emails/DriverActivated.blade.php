{{-- @slot('header')
<span style="font-size: 18px; font-weight: bold;">{{ $headerTitle }}</span>
@endslot --}}

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
<p>Congratulations! Your account has been activated by Saher Rent A Driver Team as a driver.</p>

Thanks,
**Sahar Rent a Driver** --}}
{{-- {{ config('app.name') }} --}}
{{-- @endcomponent --}}
<!DOCTYPE html>
<html>
<head>
    <title>Driver Activation Email</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="text-align: center; margin-bottom: 30px;">
        <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="Sahar Rent a Driver Logo" style="height: 100px;">
    </div>

    <h2>Hi {{ $message['name'] }},</h2>

    <p>
        Congratulations! Your account has been <strong>activated</strong> by the <strong>Sahar Rent A Driver</strong>.
    </p>

   
    <p>
        Thanks,<br>
        Sahar Rent a Driver
    </p>
</body>
</html>

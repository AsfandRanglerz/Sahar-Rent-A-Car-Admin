{{-- @component('mail::message')
# Welcome {{ $name }}!

Your account has been successfully created. Below are your login details:

**Email:** {{ $email }}
**Phone:** {{ $phone }}
**Password:** {{ $password }}


Please make sure to change your password after logging in to keep your account secure.

Thanks,
{{ config('app.name') }}
@endcomponent --}}

<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Sahar Rent a Driver</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <div style="text-align:center; margin-bottom: 20px;">
        <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" 
             alt="{{ config('app.name') }} Logo" 
             style="height: 100px; margin-bottom: 20px;">
        <h3><strong>Welcome to <span style="color: #021642;">Sahar Rent a Driver</span></strong></h3>
    </div>

    <p>Dear {{ $name ?? 'User' }},</p>

    <p>Your account has been successfully created.</p>

    <p>With your account, you’ll be able to:</p>
    <ul>
        <li>View and accept assigned pickup and drop-off requests</li>
        <li>Track and manage your booking schedule</li>
        <li>Accept or reject new requests</li>
    </ul>

    <h3>Your Account Details:</h3>
    <ul>
        <li><strong>Email:</strong> {{ $email ?? 'N/A' }}</li>
        <li><strong>Phone:</strong> {{ $phone ?? 'N/A' }}</li>
        <li><strong>Password:</strong> {{ $password ?? 'N/A' }}</li>
    </ul>

    @if ($type == 'sub_admins')
        <p style="text-align: center; margin-top: 20px;">
            <a href="{{ url('/admin') }}" 
               style="display: inline-block; padding:10px 20px; background:#021642; color:#fff; text-decoration:none; border-radius:5px;">
                Login
            </a>
        </p>
    @endif

    <p>Please keep this information safe and secure. Do not share your login credentials with anyone.</p>

    <p>If you have any questions or need assistance, feel free to contact our support team anytime.</p>

    <p>Thanks,<br><strong>Sahar Rent a Driver</strong></p>
</body>
</html>

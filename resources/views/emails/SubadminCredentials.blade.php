{{-- @component('mail::message')
# Welcome {{ $userName }}!

Your account has been successfully created. Below are your login details:

**Email:** {{ $email }}
**Password:** {{ $password }}


Please make sure to change your password after logging in to keep your account secure.

Thanks,
{{ config('app.name') }}
@endcomponent --}}

{{-- @component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="{{ config('app.name') }} Logo"
        style="height: 100px; margin-bottom: 20px;">
    <h3><strong>Welcome to <span>Sahar Rent a Car</span></strong></h3>
</div>

# Welcome, {{ $userName }}!

<p>Your account has been successfully.</p>

<p>With your account, you’ll be able to:</p>
<ul>
    <li>Manage and assign drivers to customer bookings</li>
    <li>Oversee booking schedules and availability</li>
    <li>Update car status and availability</li>
    <li>Coordinate with drivers for pickup and drop-off arrangements</li>
    <li>Monitor booking status and customer feedback</li>
</ul>    

## Your Account Details: --}}
{{-- - **Name:** {{ $data['name'] }} --}}
{{-- **Email:** {{ $email }}
**Phone:** {{ $phone }}
**Password:** {{ $password }}

@if ($type == 'sub_admins')
    <p style="width: 160px; margin: auto;">
        <a href="{{ url('/admin') }}" style="padding:5px 10px; color:#fff; background:#021642; border-radius:5px; text-decoration:none;">
            Login
        </a>
    </p>
@endif --}}

{{-- @component('mail::button', ['url' => url('/')])
Visit Website
@endcomponent --}}

{{-- <p>Please keep this information safe and secure. Do not share your login credentials with anyone</p>

<p>If you have any questions or need assistance, feel free to contact our support team anytime.</p>

Thanks,  
**Sahar Rent a Car**
@endcomponent --}}

<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Sahar Rent A Car</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">
    <div style="text-align:center; margin-bottom: 20px;">
        <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" 
             alt="{{ config('app.name') }} Logo" 
             style="height: 100px; margin-bottom: 20px;">
        <h3><strong>Welcome to <span style="color: #021642;">Sahar Rent A Car</span></strong></h3>
    </div>

    <p>Dear {{ $userName ?? 'User' }},</p>

    <p>Your account has been successfully created.</p>

    <p>With your account, you’ll be able to:</p>
    <ul>
        <li>Manage and assign drivers to customer bookings</li>
        <li>Oversee booking schedules and availability</li>
        <li>Update car status</li>
        <li>Coordinate with drivers for pickup and drop-off arrangements</li>
        <li>Monitor booking status</li>
    </ul>

    <h3>Your Account Details:</h3>
    <ul>
        <li><strong>Email:</strong> {{ $email ?? 'N/A' }}</li>
        {{-- <li><strong>Phone:</strong> {{ $phone ?? 'N/A' }}</li> --}}
        <li><strong>Password:</strong> {{ $password ?? 'N/A' }}</li>
    </ul>

    @if ($type == 'sub_admins')
     <p>Your account has been created. You will be able to manage specific sections of the system based on the permissions granted by the admin.</p>
        <p style="text-align: center; margin-top: 20px;">
            <a href="{{ url('/admin') }}" 
               style="display: inline-block; padding:10px 20px; background:#021642; color:#fff; text-decoration:none; border-radius:5px;">
                Login
            </a>
        </p>
    @endif

    <p>Please keep this information safe and secure. Do not share your login credentials with anyone.</p>

    <p>If you have any questions or need assistance, feel free to contact our support team anytime.</p>

    <p>Thanks,<br><strong>Sahar Rent A Car</strong></p>
</body>
</html>

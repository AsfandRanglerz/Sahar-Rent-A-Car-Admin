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

@slot('header')
<span style="font-size: 18px; font-weight: bold;">{{ $headerTitle }}</span>
@endslot

@component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="{{ config('app.name') }} Logo"
        style="height: 100px; margin-bottom: 20px;">
    <h3><strong>Welcome to <span>Sahar Rent a Driver</span></strong></h3>
</div>

# Welcome, {{ $name }}!

<p>Your account has been successfully created.</p>

<p>With your account, you’ll be able to:</p>
<ul>
    <li>View and accept assigned pickup and drop-off requests</li>
    <li>Track and manage your booking schedule</li>
    <li>Accept or reject new requests</li>
</ul>

## Your Account Details:
{{-- - **Name:** {{ $data['name'] }} --}}
**Email:** {{ $email }}
{{-- **Phone:** {{ $phone }} --}}
**Password:** {{ $password }}

@if ($type == 'sub_admins')
    <p style="width: 160px; margin: auto;">
        <a href="{{ url('/admin') }}" style="padding:5px 10px; color:#fff; background:#021642; border-radius:5px; text-decoration:none;">
            Login
        </a>
    </p>
@endif

{{-- @component('mail::button', ['url' => url('/')])
Visit Website
@endcomponent --}}
<p>Please keep this information safe and secure. Do not share your login credentials with anyone</p>

<p>If you have any questions or need assistance, feel free to contact our support team anytime.</p>

Thanks,  
**Sahar Rent a Driver**
@endcomponent
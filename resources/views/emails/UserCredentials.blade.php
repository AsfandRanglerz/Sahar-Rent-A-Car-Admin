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

@component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="{{ config('app.name') }} Logo"
        style="height: 100px; margin-bottom: 20px;">
    <h3><strong>Welcome to <span>Sahar Rent a Car</span></strong></h3>
</div>

# Welcome, {{ $name }}!

Your account has been successfully created by the Admin.

## Your Account Details:
{{-- - **Name:** {{ $data['name'] }} --}}
**Email:** {{ $email }}
**Phone:** {{ $phone }}
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

If you have any questions, feel free to contact us.

Thanks,  
**Sahar Rent a Car**
@endcomponent
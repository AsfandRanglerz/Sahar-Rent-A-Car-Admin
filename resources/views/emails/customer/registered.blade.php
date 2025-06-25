{{-- @component('mail::message')
# Welcome, {{ $name }}!

Thank you for registering with us. Your account has been successfully created.

## Your Details:
- **Name:** {{ $name }}
- **Email:** {{ $email }}
- **Phone:** {{ $phone }} --}}

{{-- @component('mail::button', ['url' => url('/')])
Visit Website
@endcomponent --}}

{{-- If you have any questions, feel free to contact us.

Thanks,  
{{ config('app.name') }}
@endcomponent --}}


@component('mail::message')

@slot('header')
@component('mail::header', ['url' => config('app.url')])
<span style="font-size: 18px; font-weight: bold;">{{ $headerTitle }}</span>
@endcomponent
@endslot

<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="Logo"
        style="height: 100px; margin-bottom: 20px;">
    <h3><strong>Welcome to <span>Sahar Rent A Car</span></strong></h3>
</div>

# Welcome, {{ $name }}!

Your account has been successfully created.

## Your Account Details:
<ul>
<li>- **Name:** {{ $name }}</li>
<li>- **Email:** {{ $email }}</li>
<li>- **Phone:** {{ $phone }}</li>
</ul>
{{-- - **Password:** {{ $password }} --}}

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
**Sahar Rent A Car**
@endcomponent

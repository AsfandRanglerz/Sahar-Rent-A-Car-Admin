@slot('header')
<span style="font-size: 18px; font-weight: bold;">{{ $headerTitle }}</span>
@endslot

@component('mail::message')
{{-- <div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/images/logo.png') }}" alt="Logo" style="max-width: 100px;">
</div> --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="{{ config('app.name') }} Logo"
        style="height: 100px; margin-bottom: 20px;">
    {{-- <h3><strong>Welcome to <span>Sahar Rent a Car</span></strong></h3> --}}
</div>

<h1>Hi {{ $message['name'] }},</h1>
<p>Congratulations! Your account has been activated by Saher Rent A Driver Team as a driver.</p>

Thanks,
**Sahar Rent a Driver**
{{-- {{ config('app.name') }} --}}
@endcomponent

@component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="{{ config('app.name') }} Logo"
        style="height: 100px; margin-bottom: 20px;">
    {{-- <h3><strong>Welcome to <span>Sahar Rent a Car</span></strong></h3> --}}
</div>
# Password Reset OTP

Use the OTP below to reset your password:

**{{ $otp }}**

If you did not request for OTP, please ignore this email.

Thanks,
**Sahar Rent A Car**
{{-- {{ config('app.name') }} --}}
@endcomponent

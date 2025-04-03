@component('mail::message')

<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="{{ config('app.name') }} Logo"
        style="height: 100px; margin-bottom: 20px;">
    {{-- <h3><strong>Welcome to <span>Sahar Rent a Car</span></strong></h3> --}}
</div>
    We have received reset password request, please click below button to reset password.
@component('mail::button', ['url' => $detail['url']])
Reset Password
@endcomponent

Thanks,
**Sahar Rent A Car**
@endcomponent

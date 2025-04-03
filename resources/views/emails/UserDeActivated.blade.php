@component('mail::message')
{{-- <p style="margin:0 auto 10px;width:145px">Store Manager Deactivated</p> --}}
{{-- <div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/images/logo.png') }}" alt="Logo" style="max-width: 100px;">
</div> --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="{{ config('app.name') }} Logo"
        style="height: 100px; margin-bottom: 20px;">
    {{-- <h3><strong>Welcome to <span>Sahar Rent a Car</span></strong></h3> --}}
</div>
<h1>Hi {{ $message['name'] }},</h1>
<p>We regret to inform you that your account has been deactivated by the Saher Rent A Car team.
<h1>Reason:</h1>
<p>{{ $message['reason'] }}</p>


Thanks,
**Sahar Rent A Car**

{{-- {{ config('app.name') }} --}}
@endcomponent

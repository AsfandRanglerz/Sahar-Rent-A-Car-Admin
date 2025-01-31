@component('mail::message')
{{-- <div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/images/logo.png') }}" alt="Logo" style="max-width: 100px;">
</div> --}}

<h1>Hi {{ $message['name'] }},</h1>
<p>Congratulations! Your License has been approved by Saher Rent A Car Team.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent

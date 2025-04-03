@component('mail::message')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ asset('public/admin/assets/img/sahar_logo(1).png') }}" alt="{{ config('app.name') }} Logo"
        style="height: 100px; margin-bottom: 20px;">
    {{-- <h3><strong>Welcome to <span>Sahar Rent a Car</span></strong></h3> --}}
</div>
# New Contact Us Message

You have received a new message from your website's contact form.

**Email:** {{ $email }}

**Message:**  
{{ $message }}

@component('mail::button', ['url' => 'mailto:' . $email])
Reply to {{ $email }}
@endcomponent

Thanks,
**Sahar Rent A Car**
{{-- {{ config('app.name') }} --}}
@endcomponent

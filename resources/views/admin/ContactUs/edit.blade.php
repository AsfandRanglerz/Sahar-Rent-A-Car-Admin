@extends('admin.layout.app')
@section('title', 'Edit Contact Us')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('ContactUs.index') }}">Back</a>
                <form id="edit_subadmin" action="{{ route('ContactUs.update', $contactus->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Contact Us</h4>
                                <div class="row mx-0 px-4">
                                    
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Email</label>
                                            <input type="email" placeholder="Enter Your Email" name="email"
                                                id="email" value="{{ old('email', $contactus->email) }}" class="form-control">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Phone Number</label>
                                            <input type="number" placeholder="Enter Your Phone Number" name="phone"
                                                id="phone" value="{{ old('phone', $contactus->phone) }}" class="form-control">
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                  
                                   
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                            <label>Address</label>
                                            <input type="text" id="autocomplete" name="address"
                                                placeholder="Enter your address"
                                                value="{{ old('address', $contactus->address ?? '') }}"
                                                class="form-control" autocomplete="off">
                                            @error('address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $contactus->latitude ?? '') }}">
                                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $contactus->longitude ?? '') }}">

                                </div>
                                </div>

                                <div class="card-footer text-center">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg" id="submit">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places"></script>
<script>
    function initAutocomplete() {
        const input = document.getElementById('autocomplete');
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        let placeSelected = false;

        const autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['geocode'],
            componentRestrictions: { country: 'ae' }
        });

        // Disable Enter key (so form doesn't submit prematurely)
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') e.preventDefault();
        });

        // When user selects from Google suggestion
        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();

            if (!place.geometry) {
                showError();
                return;
            }

            latInput.value = place.geometry.location.lat();
            lngInput.value = place.geometry.location.lng();
            placeSelected = true;
        });

        // Reset placeSelected if user types or pastes
       ['input', 'paste', 'change'].forEach(eventType => {
    input.addEventListener(eventType, () => {
        placeSelected = false;
        latInput.value = '';
        lngInput.value = '';
    });
});


        // Validate on form submit
        const form = input.closest('form');
         if (form) {
            form.addEventListener('submit', function (e) {
                const hasLatLng = latInput.value && lngInput.value;

                if ((!placeSelected && !hasLatLng) || !input.value) {
                    // Clear invalid data
                    input.value = '';
                    latInput.value = '';
                    lngInput.value = '';
                    placeSelected = false;

                    // Prevent submit
                    e.preventDefault();
                    alert('Please select a valid location from the dropdown.');
                    return false;
                }
            });
        }


        // Reverse geocode if lat/lng already exist (edit form)
        function reverseGeocodeAndSetAddress() {
            if (latInput.value && lngInput.value && !input.value) {
                const geocoder = new google.maps.Geocoder();
                const latlng = { lat: parseFloat(latInput.value), lng: parseFloat(lngInput.value) };
                geocoder.geocode({ location: latlng }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        input.value = results[0].formatted_address;
                        placeSelected = true;
                    }
                });
            }
        }

        reverseGeocodeAndSetAddress();
    }

    document.addEventListener("DOMContentLoaded", initAutocomplete);
</script>

@endsection
@section('js')



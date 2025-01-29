@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('booking.index') }}">Back</a>
                <form id="add_header_content_two" action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Booking</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Customer Name</label>
                                            <input type="text" name="full_name" class="form-control" placeholder="Name"> 
                                            @error('full_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Email</label>
                                            <input type="email" placeholder="Enter Your Email" name="email"
                                                id="email" value="{{ old('email') }}" class="form-control">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>  

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Phone Number</label>
                                            <input type="number" name="phone" class="form-control" placeholder="Phone"> 
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Self Pickup</label>
                                            <select name="self_pickup" id="is_dropdown" class="form-control">
                                                <option disabled selected>Select value</option>
                                                <option value="Yes" {{ old('self_pickup') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="No" {{ old('self_pickup') == 'No' ? 'selected' : '' }}>No</option>
                                            </select>
                                            @error('self_pickup')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Pickup Address</label>
                                            <input type="text" name="pickup_address" class="form-control" placeholder="Pickup Address"> 
                                            @error('pickup_address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Pickup Date</label>
                                            <input type="date" name="pickup_date" class="form-control" placeholder="Pickup Date"> 
                                            @error('pickup_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Pickup Time</label>
                                            <input type="time" name="pickup_time" class="form-control" placeholder="Pickup Time"> 
                                            @error('pickup_time')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Self Drop Off</label>
                                            <select name="self_dropoff" id="is_dropdown" class="form-control">
                                                <option disabled selected>Select value</option>
                                                <option value="Yes" {{ old('self_dropoff') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="No" {{ old('self_dropoff') == 'No' ? 'selected' : '' }}>No</option>
                                            </select>
                                            @error('self_dropoff')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Drop Off Address</label>
                                            <input type="text" name="dropoff_address" class="form-control" placeholder="Dropoff Address"> 
                                            @error('dropoff_address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Drop Off Date</label>
                                            <input type="date" name="dropoff_date" class="form-control" placeholder="Dropoff Date"> 
                                            @error('dropoff_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Drop Off Time</label>
                                            <input type="time" name="dropoff_time" class="form-control" placeholder="dropoff_time"> 
                                            @error('dropoff_time')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Additional Notes (Optional)</label>
                                            <textarea type="description" name="driver_required" class="form-control" placeholder="Enter Message" > </textarea>
                                            @error('driver_required')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="row mx-0 px-4">
                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Message</label>
                                            <textarea type="description" name="description" class="form-control" placeholder="Enter Message" > </textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Price for above</label>
                                            <input type="number" name="above_equal_price" class="form-control" placeholder="enter price" step="0.001">
                                            @error('words_limit')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Delivery Time</label>
                                            <input type="number" name="delivery_days" class="form-control" placeholder="enter number of days"> 
                                            @error('words_limit')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>--}}
                                </div> 
                                {{-- <div class="row mx-0 px-4">
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Package Category</label>
                                            <select name="price_category" id="package" class="form-control">
                                                <option disabled selected>Select value</option>
                                                <option value="Regular" {{ old('price_category') == 'Basic' ? 'selected' : '' }}>Regular Price</option>
                                                <option value="Discounted" {{ old('price_category') == 'Advance' ? 'selected' : '' }}>Discounted Price for students and researchers in MENA Region</option>
                                            </select>
                                            @error('price_category')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="card-footer text-center">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg"
                                            id="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
@section('js')
{{-- <script>
    $(document).ready(function () {
        // When the "user_type" dropdown changes
        $('#is_dropdown').on('change', function () {
            let selectedValue = $(this).val();

            // Hide both customer and driver sections by default
            $('#customer_cat').addClass('d-none');
            $('#driver').addClass('d-none');

            // Show the appropriate section based on the selected value
            if (selectedValue === 'Customer') {
                $('#customer_cat').removeClass('d-none'); // Show the customer dropdown
            } else if (selectedValue === 'Driver') {
                $('#driver').removeClass('d-none'); // Show the driver dropdown
            }
        });
    });
</script> --}}

{{-- <script>
    $(document).ready(function () {
        // Handle dropdown visibility based on user type
        $('#is_dropdown').on('change', function () {
            let selectedValue = $(this).val();

            // Hide both sections initially
            $('#customer_cat').addClass('d-none');
            $('#driver').addClass('d-none');

            // Reset checkboxes and dropdowns
            $('#select_all_customers, #select_all_drivers').prop('checked', false);
            $('#customers, #drivers').val([]);

            // Show the appropriate section based on selection
            if (selectedValue === 'Customer') {
                $('#customer_cat').removeClass('d-none');
            } else if (selectedValue === 'Driver') {
                $('#driver').removeClass('d-none');
            }
        });

        // "Select All Customers" checkbox functionality
        $('#select_all_customers').on('change', function () {
            if ($(this).is(':checked')) {
                $('#customers option').prop('selected', true);
            } else {
                $('#customers option').prop('selected', false);
            }
        });

        // "Select All Drivers" checkbox functionality
        $('#select_all_drivers').on('change', function () {
            if ($(this).is(':checked')) {
                $('#drivers option').prop('selected', true);
            } else {
                $('#drivers option').prop('selected', false);
            }
        });

        // Ensure that unchecking any individual option also unchecks the "Select All" checkbox
        $('#customers').on('change', function () {
            if ($('#customers option').length === $('#customers option:selected').length) {
                $('#select_all_customers').prop('checked', true);
            } else {
                $('#select_all_customers').prop('checked', false);
            }
        });

        $('#drivers').on('change', function () {
            if ($('#drivers option').length === $('#drivers option:selected').length) {
                $('#select_all_drivers').prop('checked', true);
            } else {
                $('#select_all_drivers').prop('checked', false);
            }
        });
    });
</script> --}}

<script>
    $(document).ready(function () {
        // Initialize Select2
        $('.select2').select2({
            placeholder: "Select options", // Optional placeholder
            allowClear: true,
        });

        // Handle dropdown visibility based on user type
        $('#is_dropdown').on('change', function () {
            let selectedValue = $(this).val();

            // Hide both sections initially
            $('#customer_cat').addClass('d-none');
            $('#driver').addClass('d-none');

            // Reset checkboxes and dropdowns
            $('#select_all_customers, #select_all_drivers').prop('checked', false);
            $('#customers, #drivers').val([]).trigger('change');

            // Show the appropriate section based on selection
            if (selectedValue === 'Customer') {
                $('#customer_cat').removeClass('d-none');
            } else if (selectedValue === 'Driver') {
                $('#driver').removeClass('d-none');
            }
        });

        // "Select All Customers" checkbox functionality
        $('#select_all_customers').on('change', function () {
            if ($(this).is(':checked')) {
                $('#customers > option').prop('selected', true);
                $('#customers').trigger('change');
            } else {
                $('#customers > option').prop('selected', false);
                $('#customers').trigger('change');
            }
        });

        // "Select All Drivers" checkbox functionality
        $('#select_all_drivers').on('change', function () {
            if ($(this).is(':checked')) {
                $('#drivers > option').prop('selected', true);
                $('#drivers').trigger('change');
            } else {
                $('#drivers > option').prop('selected', false);
                $('#drivers').trigger('change');
            }
        });

        // Ensure that unchecking any individual option also unchecks the "Select All" checkbox
        $('#customers').on('change', function () {
            if ($('#customers option').length === $('#customers option:selected').length) {
                $('#select_all_customers').prop('checked', true);
            } else {
                $('#select_all_customers').prop('checked', false);
            }
        });

        $('#drivers').on('change', function () {
            if ($('#drivers option').length === $('#drivers option:selected').length) {
                $('#select_all_drivers').prop('checked', true);
            } else {
                $('#select_all_drivers').prop('checked', false);
            }
        });
    });
</script>

@endsection

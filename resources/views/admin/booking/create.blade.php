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
                                            <input type="text" name="full_name" class="form-control" placeholder="full_name"> 
                                            @error('full_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Phone Number</label>
                                            <input type="number" name="phone" class="form-control" placeholder="phone"> 
                                            @error('phone')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Phone Number</label>
                                            <input type="number" name="phone" class="form-control" placeholder="phone"> 
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
                                    

                                    {{-- <div class="col-sm-3 pl-sm-0 pr-sm-2 d-none" id="customer_cat">
                                        <div class="form-group mb-2">
                                            <label>Customer</label>
                                            <div>
                                                <input type="checkbox" id="select_all_customers" class="mr-1">
                                                <label for="select_all_customers">Select All Customers</label>
                                            </div>
                                            <select name="customer_name[]" id="customers" class="form-control select2" multiple>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ old('customer_name') && in_array($customer->id, old('customer_name')) ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('customer_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    
                                    {{-- <div class="col-sm-3 pl-sm-0 pr-sm-3 d-none" id="driver">
                                        <div class="form-group mb-2">
                                            <label>Driver</label>
                                            <div>
                                                <input type="checkbox" id="select_all_drivers" class="mr-1">
                                                <label for="select_all_drivers">Select All Drivers</label>
                                            </div>
                                            <select name="drivers[]" id="drivers" class="form-control select2" multiple>
                                                @foreach($drivers as $driver)
                                                    <option value="{{ $driver->id }}" {{ old('drivers') && in_array($driver->id, old('drivers')) ? 'selected' : '' }}>
                                                        {{ $driver->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('drivers')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Pickup Address</label>
                                            <input type="text" name="pickup_address" class="form-control" placeholder="pickup_address"> 
                                            @error('pickup_address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                    <div class="form-group mb-2">
                                        <label>Pickup Date</label>
                                        <input type="date" name="pickup_date" class="form-control" placeholder="pickup_date"> 
                                        @error('pickup_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                <div class="form-group mb-2">
                                    <label>Pickup Time</label>
                                    <input type="time" name="pickup_time" class="form-control" placeholder="pickup_time"> 
                                    @error('pickup_time')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 pl-sm-0 pr-sm-3">
                            <div class="form-group mb-2">
                                <label>Pickup Time</label>
                                <input type="time" name="pickup_time" class="form-control" placeholder="pickup_time"> 
                                @error('pickup_time')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                                <div class="row mx-0 px-4">
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Message</label>
                                            <textarea type="description" name="description" class="form-control" placeholder="Enter Message" > </textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
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

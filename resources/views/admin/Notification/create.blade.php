@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                {{-- <a class="btn btn-primary mb-3" href="{{ route('servicePrice.index') }}">Back</a> --}}
                <form id="add_header_content_two" action="{{ route('notification.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Notifications</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>User Type</label>
                                            <select name="user_type" id="is_dropdown" class="form-control" required>
                                                <option value="" selected>Select value</option>
                                               
                                                <option value="Customer" {{ old('user_type') == 'Customer' ? 'selected' : '' }}>Customer</option>
                                                <option value="Driver" {{ old('user_type') == 'Driver' ? 'selected' : '' }}>Driver</option>
                                                
                                            </select>
                                            @error('user_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    

                                    <div class="col-sm-3 pl-sm-0 pr-sm-2 d-none" id="customer_cat">
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
                                    </div>
                                    
                                    <div class="col-sm-3 pl-sm-0 pr-sm-3 d-none" id="driver">
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
                                    </div>
                                    
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Title</label>
                                            <input type="title" name="title" class="form-control" placeholder="Title"> 
                                            @error('title')
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
                                    
                                    
                                </div> 
                                

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

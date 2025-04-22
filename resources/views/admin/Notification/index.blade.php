@extends('admin.layout.app')
@section('title', 'index')
@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Notifications</h4>
                                </div>
                            </div>
                            <div class="card-body  table-striped table-bordered table-responsive">
                                @php
                                $isAdmin = $isAdmin ?? false;
                               $permissions = $subadminPermissions['notifications'] ?? null;
                                // Fetch permissions for this menu
                               @endphp 
                           @if($isAdmin || ($permissions && $permissions->add == 1)) 
                                <a  class="btn btn-primary mb-3" data-toggle="modal" data-target="#createNotificationModal">
                                    Create
                                </a>
                                <form action="{{ route('notifications.deleteAll') }}" method="POST" class="d-inline-block float-right"
                      >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger mb-3 delete_all">
                        Delete All
                    </button>
                </form>
                            @endif
                                <table class="responsive table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            {{-- <th>User</th>
                                            <th>Customer Name</th>
                                            <th>Driver Name</th> --}}
                                            <th>Title</th>
                                            <th>Message</th>
                                            <th>Created At</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($notifications as $notification)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                {{-- <td>{{ $notification->user_type}}</td>
                                                <td>{{ $notification->customer_name }}</td>
                                                <td>{{ $notification->drivers }}</td> --}}
                                                <td>{{ $notification->title }}</td>
                                                <td>{{ $notification->description }}</td>
                                                <td>{{ $notification->created_at->format('d M Y') }}</td>
                                                {{-- <td>{{ $notification->additional_services}}</td>
                                                @if($notification->packages == null)
                                                    <td class="text-danger">
                                                        <span>No record found</span>
                                                    </td>
                                                @else
                                                    <td>{{ $notification->packages }}</td>
                                                @endif
                                                <td>{{ $notification->price }}$</td> --}}
                                                {{-- <td>{{ $notification->less_equal_price }} $</td>
                                                <td>{{ $notification->above_equal_price }} per word</td>
                                                <td>{{ $notification->delivery_days }} days</td> --}}
                                               

                                                {{-- <td>
                                                    <div class="badge {{ $notification->status == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
                                                        {{ $notification->status == 0 ? 'Activated' : 'Deactivated' }}
                                                    </div>
                                                </td> --}}
                                                <td>
                                                    <div class="d-flex gap-4">
                                                        @if($isAdmin || ($permissions && $permissions->edit == 1))
                                                        {{-- <a href="{{route('notification.Edit',$notification->id)}}"
                                                            class="btn btn-primary" style="margin-left: 10px">
                                                            <span class="fas fa-edit"></span> </a> --}}
                                                            @endif
                                                            @if($isAdmin || ($permissions && $permissions->delete == 1))    
                                                            <form action="{{ route('notification.destroy', $notification->id) }}" method="POST" style="display:inline-block; margin-left: 10px">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-flat show_confirm" data-toggle="tooltip">
                                                                    <span class="fas fa-trash-alt"></span> <!-- Delete icon -->
                                                                </button>
                                                            </form>
                                                            @endif
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                {{-- @include('admin.Notification.create') --}}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
        </section>

    </div>

    <div class="modal fade" id="createNotificationModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Create Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
                    <form id="add_header_content_two" action="{{ route('notification.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
    
                        <!-- User Type Selection -->
                        <div class="form-group">
                            <label><strong>User Type <span class="text-danger">*</span></strong></label>
                            <select name="user_type" id="user_type" class="form-control">
                                <option disabled selected>Select User Type</option>
                                <option value="Customer">Customer</option>
                                <option value="Driver">Driver</option>
                            </select>
                            @error('user_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <!-- Customers Selection -->
                        <div class="form-group d-none" id="customer_field">
                            <label><strong>Customers <span class="text-danger">*</span></strong></label>
                            <div class="form-check">
                                <input type="checkbox" id="select_all_customers" class="form-check-input">
                                <label class="form-check-label" for="select_all_customers">Select All</label>
                            </div>
                            <select name="customers[]" id="customers" class="form-control select2" multiple>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customers') && in_array($customer->id, old('customers')) ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customers')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <!-- Drivers Selection -->
                        <div class="form-group d-none" id="driver_field">
                            <label><strong>Drivers <span class="text-danger">*</span></strong></label>
                            <div class="form-check">
                                <input type="checkbox" id="select_all_drivers" class="form-check-input">
                                <label class="form-check-label" for="select_all_drivers">Select All</label>
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
    
                        <!-- Title Input -->
                        <div class="form-group">
                            <label><strong>Title <span class="text-danger">*</span></strong></label>
                            <input type="text" name="title" class="form-control" placeholder="Title" required>
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <!-- Message Textarea -->
                        <div class="form-group">
                            <label><strong>Message</strong></label>
                            <textarea name="description" class="form-control" placeholder="Type your message here..." rows="4" required></textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <!-- Submit Button -->
                        <div class="text-right">
                            {{-- <button type="submit" class="btn btn-primary">Create</button> --}}
                            <button type="submit" class="btn btn-primary" id="createBtn">
                                <span id="createBtnText">Create</span>
                                <span id="createSpinner" style="display: none;">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
   
    

    
    

@endsection

@section('js')
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
    $(document).on('click', '.show_confirm', function(event) {
        var form = $(this).closest("form");
        event.preventDefault();
        swal({
            title: "Are you sure you want to delete this record?",
            text: "If you delete this, it will be gone forever.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        });
    });

     $(document).ready(function () {
             // Initialize Select2
             $('.select2').select2({
                 placeholder: "Select options",
                 allowClear: true,
             });
    
              //Handle user type selection
             $('#user_type').on('change', function () {
                 let selectedType = $(this).val();
    
                 // Hide both sections initially
                 $('#customer_field, #driver_field').addClass('d-none');
    
                 // Reset checkboxes and dropdowns
                 $('#select_all_customers, #select_all_drivers').prop('checked', false);
                 $('#customers, #drivers').val([]).trigger('change');
    
                 // Show the appropriate section
                 if (selectedType === 'Customer') {
                     $('#customer_field').removeClass('d-none');
                 } else if (selectedType === 'Driver') {
                     $('#driver_field').removeClass('d-none');
                 }
             });
    
            //  "Select All Customers" functionality
             $('#select_all_customers').on('change', function () {
                 $('#customers > option').prop('selected', this.checked).trigger('change');
             });
    
            //  "Select All Drivers" functionality
             $('#select_all_drivers').on('change', function () {
                 $('#drivers > option').prop('selected', this.checked).trigger('change');
             });
    
             // Ensure "Select All" is unchecked if any individual option is deselected
             $('#customers').on('change', function () {
                 $('#select_all_customers').prop('checked', $('#customers option:selected').length === $('#customers option').length);
             });
    
             $('#drivers').on('change', function () {
                 $('#select_all_drivers').prop('checked', $('#drivers option:selected').length === $('#drivers option').length);
             });
             $('form').submit(function () {
        // Show spinner and disable button
        $("#createSpinner").show();
        $("#createBtnText").hide();
        $("#createBtn").prop("disabled", true);
    });
         });

         $(document).on('click', '.delete_all', function(event) {
        var form = $(this).closest("form");
        event.preventDefault();
        swal({
            title: "Are you sure you want to delete all records?",
            text: "This will permanently remove all records and cannot be undone.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        });
    });
</script>
@endsection

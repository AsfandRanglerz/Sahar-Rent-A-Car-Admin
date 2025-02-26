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
                                    <h4>Request Bookings</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                 @php
                                $isAdmin = $isAdmin ?? false;
                               $permissions = $subadminPermissions['requestbookings'] ?? null;
                                // Fetch permissions for this menu
                               @endphp 
                           {{--@if($isAdmin || ($permissions && $permissions->add == 1))  --}}
                                {{-- <a class="btn btn-primary mb-3" href="{{ route('booking.create') }}">Create
                                </a> --}}
{{-- @endif --}}
                                <table class="responsive table " id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Car Id</th>
                                            <th>Status</th>
                                            {{-- <th>Driver</th> --}}
                                            <th>Customer</th>
                                            <th>Email Address</th>
                                            <th>Phone Number</th>
                                            <th>Self Pickup</th>
                                            <th>Pickup Address</th>
                                            <th>Pickup Date</th>
                                            <th>Pickup Time</th>
                                            <th>Self Drop Off</th>
                                            <th>Drop Off Address</th>
                                            <th>Drop Off Date</th>
                                            <th>Drop Off Time</th>
                                            <th>Additional Notes</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($requestbookings as $requestbooking)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $requestbooking->car_id }}</td>
                                                <td>
                                                    {{-- <div class="badge {{ $requestbooking->status == 0 ? 'badge-success' : 'badge-primary' }} badge-shadow">
                                                        {{ $requestbooking->status == 0 ? 'Active' : 'Completed' }}
                                                    </div> --}}
                                                @if($requestbooking->status == 2)
                                                    <div class="badge badge-warning badge-shadow">Pending</div>
                                                    @elseif($requestbooking->status == 0)
                                                    <div class="badge badge-success badge-shadow">Active</div>
                                                @endif
                                                </td>
                                                <td>{{ $requestbooking->full_name }}</td>
                                                {{-- <td>
                                                @if($requestbooking->driver_name)    
                                                    {{ $requestbooking->driver_name }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td> --}}
                                                <td>
                                                    @if ($requestbooking->email)
                                                        <a href="mailto:{{ $requestbooking->email }}">{{ $requestbooking->email }}</a>
                                                    @endif
                                                </td>
                                                <td>{{ $requestbooking->phone }}</td>

                                                
                                                <td>{{ $requestbooking->self_pickup }}</td>
                                                {{-- <td>{{ $requestbooking->durations }}</td> --}}
                                                {{-- <td>{{ $requestbooking->call_number }}</td>
                                                <td>{{ $requestbooking->whatsapp_number }}</td> --}}
                                                <td>
                                                @if($requestbooking->pickup_address)    
                                                    {{ $requestbooking->pickup_address }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td>
                                                <td>
                                                @if($requestbooking->pickup_date)    
                                                    {{ $requestbooking->pickup_date }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td>
                                                <td>
                                                @if($requestbooking->pickup_time)    
                                                    {{ $requestbooking->pickup_time }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td>
                                                <td>{{ $requestbooking->self_dropoff }}</td>
                                                <td>{{ $requestbooking->dropoff_address }}</td>
                                                <td>{{ $requestbooking->dropoff_date }}</td>
                                                <td>{{ $requestbooking->dropoff_time }}</td>
                                                <td>
                                                @if($requestbooking->driver_required) 
                                                    {{ $requestbooking->driver_required }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td>
                                                 {{-- <td>{!! $requestbooking->car_play !!}</td> --}}
                                                 {{-- <td>
                                                    @if (!empty($requestbooking->car_play))
                                                        @php
                                                            $features = explode("\n", $requestbooking->car_play); // Assuming features are stored as a comma-separated string
                                                        @endphp
                                                        <ul>
                                                            @foreach ($features as $feature)
                                                                <li>{{ trim($feature) }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td> --}}
                                            
                                                {{--<td>{{ $requestbooking->delivery }}</td>
                                                <td>{{ $requestbooking->pickup }}</td>
                                                <td>{{ $requestbooking->travel_distance }}</td> --}} 
                                                {{-- <td>
                                                    <div class="badge {{ $requestbooking->status == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
                                                        {{ $requestbooking->status == 0 ? 'Activated' : 'Deactivated' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <img src="{{ asset($requestbooking->image) }}" alt="" height="50"
                                                        width="50" class="image">
                                                </td> --}}

                                                <td>
                                                    <div class="d-flex gap-4">
                                                        <div class="gap-3"
                                                            style="display: flex; align-items: left; justify-content: center; column-gap: 8px">



                                                            {{-- @if ($user->status == 1)
                                                                <a href="javascript:void(0);"
                                                                    onclick="showDeactivationModal({{ $user->id }})"
                                                                    class="btn btn-success">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-toggle-left">
                                                                        <rect x="1" y="5" width="22" height="14"
                                                                            rx="7" ry="7"></rect>
                                                                        <circle cx="16" cy="12" r="3">
                                                                        </circle>
                                                                    </svg>
                                                                </a>
                                                            @elseif($user->status == 0)
                                                                <a href="javascript:void(0);"
                                                                    onclick="showActivationModal({{ $user->id }})"
                                                                    class="btn btn-btn btn-danger">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-toggle-left">
                                                                        <rect x="1" y="5" width="22" height="14"
                                                                            rx="7" ry="7"></rect>
                                                                        <circle cx="16" cy="12" r="3">
                                                                        </circle>
                                                                    </svg>
                                                                </a>
                                                            @endif --}}

                                                            @if($isAdmin || ($permissions && $permissions->edit == 1))
                                                            {{-- <a href="{{ route('requestbooking.edit', $requestbooking->id) }}" 
                                                                class="btn btn-primary" style="margin-left: 10px">Assign</a>  --}}
                                                                <a href="javascript:void(0);" class="btn btn-primary assign-driver-btn" 
                                                                data-id="{{ $requestbooking->id }}" style="margin-left: 10px" data-toggle="modal" 
                                                                data-target="#assignDriverModal">
                                                                    Assign
                                                                </a>

                                                           @endif
                                                            @if($isAdmin || ($permissions && $permissions->delete == 1))
                                                                <form action="{{ route('requestbooking.destroy', $requestbooking->id) }}"
                                                                method="POST"
                                                                style="display:inline-block; margin-left: 1px">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-flat show_confirm"
                                                                    data-toggle="tooltip">Delete</button>
                                                            </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

   <!-- Assign Driver Modal -->
<div class="modal fade" id="assignDriverModal" tabindex="-1" aria-labelledby="assignDriverModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignDriverModalLabel">Assign Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="assignDriverForm" action="{{ route('requestbooking.edit', $requestbooking->id) }}" method="POST">
                    @csrf
                    <input type="hidden" id="requestBookingId" name="request_booking_id">
                    
                    <div class="form-group">
                        <label for="driver_id">Select Driver</label>
                        <select class="form-control" id="driver_id" name="driver_id" required>
                            <option value="">-- Select Driver --</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Assign Driver</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#table_id_events').DataTable();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table_id_events').DataTable(); // Initialize DataTable
    
            // Use event delegation to handle dynamically added elements
            $(document).on('click', '.show_confirm', function(event) {
                event.preventDefault();
                var form = $(this).closest("form");
    
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
        });
    </script>
<script>
    $(document).on('click', '.assign-driver-btn', function() {
    let requestBookingId = $(this).data('id');  // Correct way to get data-id
    console.log("Clicked Assign - Request Booking ID:", requestBookingId); // Debugging
    $('#requestBookingId').val(requestBookingId);  // Set the hidden input value



        // AJAX form submission
        $('#assignDriverForm').on('submit', function(e) {
            e.preventDefault();

            let url = $(this).attr('action'); // Get form action URL
            let formData = $(this).serialize(); // Serialize form data

            console.log("Generated URL:", url); // Debugging

            $.ajax({
                url: url,  
                type: "POST", // Change to PUT if necessary
                data: formData,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    console.log("Success:", response);
                    alert('Driver Assigned Successfully!');
                    $('#assignDriverModal').modal('hide');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error("Error Details:", xhr.responseText);
                    alert('This driver is already assigned for this date and time. please select another driver');
                }
            });
        });
    });
</script>



@endsection

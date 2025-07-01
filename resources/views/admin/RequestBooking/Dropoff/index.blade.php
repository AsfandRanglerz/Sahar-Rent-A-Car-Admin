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
                                    <form id="statusFilter" action="{{ route('dropoffs.index') }}" method="GET">
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <h4>Pickup Requests</h4>
                                            <select name="status" id="statusdropoffFilter" class="form-control form-select w-auto rounded">
                                                <option value="">All</option>
                                                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Requested" {{ request('status') == 'Requested' ? 'selected' : '' }}>Requested</option>
                                            </select>
                                        </div>
                                    </form>
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
                                            {{-- <th>Self Pickup</th>
                                            <th>Pickup Address</th>
                                            <th>Pickup Date</th>
                                            <th>Pickup Time</th> --}}
                                            <th>Self Drop Off</th>
                                            <th>Pickup Address</th>
                                            <th>Pickup Date</th>
                                            <th>Pickup Time</th>
                                            <th>City</th>
                                            <th>Price (AED)</th>
                                            <th>Delivery Charge (AED)</th>
                                            <th>Redeemed Points</th>
                                            <th>Total Days</th>
                                            <th>VAT</th>
                                            <th>Additional Notes</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dropoffs as $dropoff)
                                        @php
                                        $assignedDrivers = $dropoff->assign->whereNotNull('dropoff_driver_id');
                                        // $driverCompleted = $dropoff->assign
                                        //     ->whereNotNull('dropoff_driver_id')
                                        //     ->contains(function ($assigned) {
                                        //         return $assigned->status == 1;
                                        //     });
                                        $statusLabel = 'Unknown';

if ($dropoff->status == 2) {
    $statusLabel = 'Pending';
} elseif ($assignedDrivers->contains(function ($driver) {
    return $driver->status == 1;
})) {
    $statusLabel = 'Completed';
} elseif ($assignedDrivers->contains(function ($driver) {
    return $driver->status == 0;
})) {
    $statusLabel = 'Active';
} elseif ($assignedDrivers->contains(function ($driver) {
    return $driver->status == 3;
})) {
    $statusLabel = $assignedDrivers->contains(function ($driver) {
        return is_null($driver->dropoff_driver_id);
    }) ? 'Pending' : 'Requested';
} elseif ($dropoff->status == 0) {
    $statusLabel = 'Active';
} elseif ($dropoff->status == 3 || $dropoff->status == 1) {
    $statusLabel = 'Pending';
}

                                    @endphp
                                            <tr data-status="
                                            {{ $statusLabel }}
                                        ">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $dropoff->car_id }}</td>
                                                <td>
                                                    {{-- <div class="badge {{ $dropoff->status == 0 ? 'badge-success' : 'badge-primary' }} badge-shadow">
                                                        {{ $dropoff->status == 0 ? 'Active' : 'Completed' }}
                                                    </div> --}}
                                                {{-- @if($dropoff->status == 2)
                                                @if(is_null($dropoff->dropoff_driver_id))
                                                    <div class="badge badge-warning badge-shadow">Pending</div>
                                                    @else
                                                    <div class="badge badge-warning badge-shadow">Requested</div>
                                                @endif 
                                                    @elseif($dropoff->status == 0)
                                                    <div class="badge badge-success badge-shadow">Active</div>
                                                    @elseif($dropoff->status == 1)
                                                    <div class="badge badge-primary badge-shadow">Completed</div>
                                                    @elseif($dropoff->status == 3)
                                                    @if(is_null($dropoff->dropoff_driver_id))
                                                    <div class="badge badge-warning badge-shadow">Pending</div>
                                                @else
                                                    <div class="badge badge-warning badge-shadow">Requested</div>
                                                @endif
                                                    
                                                @endif --}}
                                               
                                            
                                                @if($statusLabel == 'Pending')
                                                <div class="badge badge-warning badge-shadow">Pending</div>
                                            @elseif($statusLabel == 'Active')
                                                <div class="badge badge-success badge-shadow">Active</div>
                                            @elseif($statusLabel == 'Completed')
                                                <div class="badge badge-primary badge-shadow">Completed</div>
                                            @elseif($statusLabel == 'Requested')
                                                <div class="badge badge-warning badge-shadow">Requested</div>
                                            @else
                                                <div class="badge badge-secondary badge-shadow">Unknown</div>
                                            @endif

                                            
                                                </td>
                                                <td>{{ $dropoff->full_name }}</td>
                                                {{-- <td>
                                                @if($dropoff->driver_name)    
                                                    {{ $dropoff->driver_name }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td> --}}
                                                <td>
                                                    @if ($dropoff->email)
                                                        <a href="mailto:{{ $dropoff->email }}">{{ $dropoff->email }}</a>
                                                    @endif
                                                </td>
                                                <td>{{ $dropoff->phone }}</td>

                                                
                                                {{-- <td>{{ $dropoff->self_pickup }}</td> --}}
                                                {{-- <td>{{ $dropoff->durations }}</td> --}}
                                                {{-- <td>{{ $dropoff->call_number }}</td>
                                                <td>{{ $dropoff->whatsapp_number }}</td> --}}
                                                {{-- <td>
                                                @if($dropoff->pickup_address)    
                                                    {{ $dropoff->pickup_address }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td>
                                                <td>
                                                @if($dropoff->pickup_date)    
                                                    {{ $dropoff->pickup_date }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td>
                                                <td>
                                                @if($dropoff->pickup_time)    
                                                    {{ $dropoff->pickup_time }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td> --}}
                                                <td>{{ $dropoff->self_dropoff }}</td>
                                                <td>
                                                    @if($dropoff->dropoff_address)
                                                    {{ $dropoff->dropoff_address }}
                                                    @else
                                                    <span>--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($dropoff->dropoff_date)
                                                    {{ $dropoff->dropoff_date }}
                                                    @else
                                                    <span>--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($dropoff->dropoff_time)
                                                     {{ \Carbon\Carbon::createFromFormat('H:i:s', $dropoff->dropoff_time)->format('g:i A') }}
                                                @else
                                                <span>--</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($dropoff->city)
                                                    {{ $dropoff->city }}
                                                @else
                                                    <span>--</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($dropoff->price)
                                                {{ $dropoff->price }} 
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td>
                                                <td>
                                                @if($dropoff->transfer_charge)
                                                    {{ $dropoff->transfer_charge }} 
                                                    @else
                                                    <span>--</span>
                                                @endif 
                                                </td>
                                                <td>
                                                    @if($dropoff->redeemed_points)
                                                        {{ $dropoff->redeemed_points }}
                                                    @else
                                                        <span>--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($dropoff->total_days)
                                                        {{ $dropoff->total_days }}
                                                    @else
                                                        <span>--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($dropoff->vat)
                                                        {{ $dropoff->vat }}%
                                                    @else
                                                        <span>--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                @if($dropoff->driver_required) 
                                                    {{ $dropoff->driver_required }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td>
                                                 {{-- <td>{!! $dropoff->car_play !!}</td> --}}
                                                 {{-- <td>
                                                    @if (!empty($dropoff->car_play))
                                                        @php
                                                            $features = explode("\n", $dropoff->car_play); // Assuming features are stored as a comma-separated string
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
                                            
                                                {{--<td>{{ $dropoff->delivery }}</td>
                                                <td>{{ $dropoff->pickup }}</td>
                                                <td>{{ $dropoff->travel_distance }}</td> --}} 
                                                {{-- <td>
                                                    <div class="badge {{ $dropoff->status == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
                                                        {{ $dropoff->status == 0 ? 'Activated' : 'Deactivated' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <img src="{{ asset($dropoff->image) }}" alt="" height="50"
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
                                                            {{-- <a href="{{ route('dropoff.edit', $dropoff->id) }}" 
                                                                class="btn btn-primary" style="margin-left: 10px">Assign</a>  --}}
                                                                <a href="javascript:void(0);" class="btn btn-primary assign-driver-btn" 
                                                                data-id="{{ $dropoff->id }}" data-toggle="modal" 
                                                                data-target="#assignDriverModal">
                                                                    Assign Driver
                                                                </a>
                                                                @php
                                                                // Check if assigned driver status is 0
                                                                // $assignedDriver = $dropoff->assign->whereNotNull('dropoff_driver_id')->first();
                                                                $hasActiveDriver = $dropoff->assign
                                                                                    ->whereNotNull('dropoff_driver_id')
                                                                                    ->where('status', 0)
                                                                                    ->sortByDesc('id') // or 'created_at' if you prefer
                                                                                    ->first();
                                                                // $isDisabled = !($assignedDriver && $assignedDriver->status == 0);
                                                            @endphp
                                                        
                                                            @if($hasActiveDriver)
                                                                <form action="{{ route('dropoff.markCompleted', $dropoff->id) }}" 
                                                                      method="POST" style="display:inline-block; margin-left: 10px;">
                                                                    @csrf
                                                                                    <button type="submit" class="btn btn-success">  {{--{{ $isDisabled ? 'disabled' : '' }} --}}
                                                                        Mark as Completed
                                                                    </button>
                                                                </form>
                                                            @endif
                                                           @endif
                                                            @if($isAdmin || ($permissions && $permissions->delete == 1))
                                                                <form action="{{ route('dropoff.destroy', $dropoff->id) }}"
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
                {{-- @foreach ($requestbookings as $requestbooking) --}}
                {{-- @if(isset($requestbooking)) --}}
                <form id="assignDriverForm" method="POST" action="">
                   {{-- @endforeach --}}
                   {{-- @else
                        <form id="assignDriverForm" action="#" method="POST">
                    @endif --}}
                    @csrf

                    <input type="hidden" id="requestBookingId" name="request_booking_id">
                    <input type="hidden" name="self_dropoff" value="No">

                    
                    <div class="form-group">
                        <label for="driver_id">Select Driver</label>
                        <select class="form-control" id="dropoff_driver_id" name="dropoff_driver_id" required>
                            <option value="">-- Select Driver --</option>
                            @foreach($drivers as $driver)
                            @if($driver->is_available == 1)
                                <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- <button type="submit" class="btn btn-primary">Assign Driver</button> --}}
                    <button type="submit" class="btn btn-primary" id="assignDriverBtn">
                        <span id="assignDriverBtnText">Assign Driver</span>
                        <span id="assignDriverSpinner" style="display: none;">
                            <i class="fa fa-spinner fa-spin"></i>
                        </span>
                    </button>
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
    // $(document).on('click', '.assign-driver-btn', function() {
    // let requestBookingId = $(this).data('id');  // Correct way to get data-id
    // console.log("Clicked Assign - Request Booking ID:", requestBookingId); // Debugging
    // $('#requestBookingId').val(requestBookingId);  // Set the hidden input value



    //     // AJAX form submission
    //     $('#assignDriverForm').on('submit', function(e) {
    //         e.preventDefault();

    //         let url = $(this).attr('action'); // Get form action URL
    //         if (url === "#") {
    //     alert("Invalid request booking selected.");
    //     return;
    // }
    //         let formData = $(this).serialize(); // Serialize form data

    //         console.log("Generated URL:", url); // Debugging

    //         $.ajax({
    //             url: url,  
    //             type: "POST", 
    //             data: formData,
    //             headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    //             success: function(response) {
    //                 console.log("Success:", response);
    //                 alert('Driver Assigned Successfully!');
    //                 $('#assignDriverModal').modal('hide');
    //                 location.reload();
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error("Error Details:", xhr.responseText);
    //                 alert('This driver is already assigned for this date and time. please select another driver');
    //             }
    //         });
    //     });
    // });
    $(document).ready(function() {
    // Assign button click - update form action
    $(document).on('click', '.assign-driver-btn', function() {
        let requestBookingId = $(this).data('id');
        console.log("Clicked Assign - Request Booking ID:", requestBookingId);

        $('#requestBookingId').val(requestBookingId);
        let baseUrl = "{{ url('admin/requestbooking') }}"; // Get the correct base URL from Laravel
    let formAction = baseUrl + "/" + requestBookingId + "/edit";
        $('#assignDriverForm').attr('action', formAction);
    });

    // Prevent duplicate event binding
    $('#assignDriverForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        let url = $(this).attr('action'); // Get updated action URL
        let formData = $(this).serialize();

        $("#assignDriverSpinner").show();
        $("#assignDriverBtnText").hide();
        $("#assignDriverBtn").prop("disabled", true);
        console.log("Submitting to URL:", url);

        $.ajax({
            url: url,
            type: "POST",
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
                let msg = 'This driver is already assigned for this date and time. Please select another driver.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                alert(msg);
            },
            complete: function() {
                // Hide spinner, show text, enable button after request completes
                $("#assignDriverSpinner").hide();
                $("#assignDriverBtnText").show();
                $("#assignDriverBtn").prop("disabled", false);
            }
        });
    });
});

document.getElementById('Filter').addEventListener('change', function() {
    let selectedStatus = this.value;
    document.querySelectorAll('#table-1 tbody tr').forEach(function(row) {
        let rowStatus = row.getAttribute('data-status').trim();

        if (selectedStatus === "" || rowStatus === selectedStatus) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>

<script>
    document.getElementById('statusdropoffFilter').addEventListener('change', function () {
        document.getElementById('statusFilter').submit();
    });
</script>

@endsection








{{-- @if($dropoff->status == 2) --}}
{{-- Always show Pending from request_booking table --}}
{{-- <div class="badge badge-warning badge-shadow">Pending</div>

@elseif($driverCompleted) --}}
{{-- Show Completed if any driver_id status is 1 --}}
{{-- <div class="badge badge-primary badge-shadow">Completed</div>

@elseif($dropoff->status == 0)
<div class="badge badge-success badge-shadow">Active</div>

@elseif($dropoff->status == 3 || $dropoff->status == 1)
@if($assigned)
    @if($assigned->status == 0)
        <div class="badge badge-success badge-shadow">Active</div> --}}
    {{-- @elseif($assigned->status == 1)
        <div class="badge badge-primary badge-shadow">Completed</div> --}}
    {{-- @elseif($assigned->status == 3)
        @if(is_null($assigned->dropoff_driver_id))
            <div class="badge badge-warning badge-shadow">Pending</div>
        @else
            <div class="badge badge-warning badge-shadow">Requested</div>
        @endif
    @else
        <div class="badge badge-secondary badge-shadow">Unknown</div>
    @endif
@else --}}
    {{-- Status is 3 but assigned entry not yet made --}}
    {{-- <div class="badge badge-warning badge-shadow">Pending</div>
@endif

@else
<div class="badge badge-secondary badge-shadow">Unknown</div>
@endif --}}
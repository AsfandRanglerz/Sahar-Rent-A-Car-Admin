@extends('admin.layout.app')
@section('title', 'index')
@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h4>Bookings</h4>
                                </div>
                                <style>
                                    .date-wrapper {
                                        position: relative;
                                    }
                                    .date-wrapper input:valid + .placeholder,
                                    .date-wrapper input:focus + .placeholder {
                                        display: none;
                                    }
                                    .date-wrapper .placeholder {
                                        position: absolute;
                                        top: 7px;
                                        left: 10px;
                                        color: #999;
                                        pointer-events: none;
                                    }
                                </style>
                                
                                <div class="text-end">
                                    <h5>Total Income: <span id="totalIncome">{{ number_format($totalIncome, 2) }}</span></h5>
                                    <form method="GET" action="{{ route('booking.index') }}" class="d-flex mt-2 me-3">
                                        <input id="startDate" name="start_date" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control" placeholder="Start Date" style="border-radius: 5px; margin-right:10px; height:37px;" value="{{ request('start_date') }}">
                                        <input id="endDate" name="end_date" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" class="form-control" placeholder="End Date" style="border-radius: 5px; margin-right:25px; height:37px;" value="{{ request('end_date') }}">
                                        <button type="submit" class="btn btn-primary" style="margin-right:5px; margin-bottom:15px;">Apply</button>
                                        <a href="{{ route('booking.index') }}" class="btn btn-secondary" style="margin-right:5px; margin-bottom:15px;">Reset</a>
                                        <button type="button" class="btn btn-primary" style="margin-bottom:15px;" onclick="printTable()">Print</button>
                                    </form>
                                    
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                 @php
                                $isAdmin = $isAdmin ?? false;
                               $permissions = $subadminPermissions['bookings'] ?? null;
                                // Fetch permissions for this menu
                               @endphp 
                           {{--@if($isAdmin || ($permissions && $permissions->add == 1))  --}}
                                {{-- <a class="btn btn-primary mb-3" href="{{ route('booking.create') }}">Create
                                </a> --}}
{{-- @endif --}}               <div id="printArea">
                                <table class="responsive table " id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Car Id</th>
                                            <th>Status</th>
                                            <th>Pickup Driver</th>
                                            <th>Dropoff Driver</th>
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
                                        @foreach ($bookings as $booking)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                {{-- <td>{{ $booking->car_id }}</td> --}}
                                                <td class="car-id">
                                                    {{ $booking->car_id ?? '--' }}
                                                </td>
                                                
                                                <td>
                                                    {{-- <div class="badge {{ $booking->status == 0 ? 'badge-success' : 'badge-primary' }} badge-shadow">
                                                        {{ $booking->status == 0 ? 'Active' : 'Completed' }}
                                                    </div> --}}
                                                    {{-- @if($booking->status == 0)
                                                        <div class="badge badge-success badge-shadow">Active</div>
                                                        @elseif($booking->status == 1)
                                                        <div class="badge badge-primary badge-shadow">Completed</div>
                                                        @endif --}}
                                                        @if($booking->status == 0)
                                                        <a href="javascript:void(0);" 
                                                           class="btn btn-success btn-sm update-status" 
                                                           data-id="{{ $booking->id }}" 
                                                           data-status="1" style="height:38px;">
                                                          <span class="text-white">  Active</span>
                                                        </a>
                                                    @elseif($booking->status == 1)
                                                        <div class="badge badge-primary badge-shadow">Completed</div>
                                                    @endif
                                                </td>
                                                <td class="driver-name">
                                                {{-- @if($booking->driver_name)    
                                                    {{ $booking->driver_name }}
                                                @else
                                                <span>--</span>
                                                @endif --}}
                                                @if($booking instanceof \App\Models\AssignedRequest)
                                                {{ $booking->driver ? $booking->pickupdriver->name : '--' }}
                                            @else
                                                {{ $booking->driver ? $booking->driver->name : '--' }}
                                            @endif
                                                </td>
                                                <td>
                                                    @if($booking instanceof \App\Models\AssignedRequest)
                                                    {{ $booking->dropdriver ? $booking->dropoffdriver->name : '--' }}
                                                @else
                                                    {{ $booking->dropdriver ? $booking->dropdriver->name : '--' }}
                                                @endif
                                                </td>
                                                <td>{{ $booking->full_name }}</td>
                                                <td>
                                                    @if ($booking->email)
                                                        <a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a>
                                                    @endif
                                                </td>
                                                <td>{{ $booking->phone }}</td>

                                                
                                                <td>{{ $booking->self_pickup }}</td>
                                                {{-- <td>{{ $booking->durations }}</td> --}}
                                                {{-- <td>{{ $booking->call_number }}</td>
                                                <td>{{ $booking->whatsapp_number }}</td> --}}
                                                <td>
                                                @if($booking->pickup_address)    
                                                    {{ $booking->pickup_address }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td>
                                                <td>
                                                @if($booking->pickup_date)    
                                                    {{ $booking->pickup_date }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td>
                                                <td>
                                                @if($booking->pickup_time)    
                                                    {{ $booking->pickup_time }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td>
                                                <td>{{ $booking->self_dropoff }}</td>
                                                <td>
                                                    @if($booking->dropoff_address)
                                                    {{ $booking->dropoff_address }}
                                                    @else
                                                    <span>--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($booking->dropoff_date)
                                                    {{ $booking->dropoff_date }}
                                                    @else
                                                    <span>--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($booking->dropoff_time)
                                                    {{ $booking->dropoff_time }}
                                                    @else
                                                    <span>--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                @if($booking->driver_required) 
                                                    {{ $booking->driver_required }}
                                                @else
                                                <span>--</span>
                                                @endif
                                                </td>
                                                 {{-- <td>{!! $booking->car_play !!}</td> --}}
                                                 {{-- <td>
                                                    @if (!empty($booking->car_play))
                                                        @php
                                                            $features = explode("\n", $booking->car_play); // Assuming features are stored as a comma-separated string
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
                                            
                                                {{--<td>{{ $booking->delivery }}</td>
                                                <td>{{ $booking->pickup }}</td>
                                                <td>{{ $booking->travel_distance }}</td> --}} 
                                                {{-- <td>
                                                    <div class="badge {{ $booking->status == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
                                                        {{ $booking->status == 0 ? 'Activated' : 'Deactivated' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <img src="{{ asset($booking->image) }}" alt="" height="50"
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

                                                            {{-- @if($isAdmin || ($permissions && $permissions->edit == 1)) --}}
                                                            {{-- <a href="{{ route('car.edit', $booking->id) }}"
                                                                class="btn btn-primary" style="margin-left: 10px">Edit</a> --}}
                                                            {{-- @endif --}}
                                                            @if($isAdmin || ($permissions && $permissions->delete == 1))
                                                                <form action="{{ route('booking.destroy', $booking->id) }}"
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
            </div>
        </section>
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
{{-- <script>
    $(document).on('click', '.update-status', function() {
    let bookingId = $(this).data('id'); // Get booking ID
    let newStatus = $(this).data('status'); // New status (1 = Completed)
    
    if (!confirm("Are you sure you want to mark this booking as completed?")) {
        return;
    }

    $.ajax({
        url: "{{ url('/admin/booking') }}/" + bookingId + "/update-status", // Laravel route
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}", // CSRF Token for security
            // booking_id: bookingId,
            status: newStatus
        },
        success: function(response) {
            if (response.success) {
                toastr.success('Booking marked as completed!');
                location.reload(); // Refresh page to update UI
            } else {
                toastr.error('Error updating status. Try again.');
            }
        },
        error: function(xhr) {
            console.error("Error:", xhr.responseText);
            toastr.error('Something went wrong.');
        }
    });
});

    </script> --}}
    <script>
        $(document).on('click', '.update-status', function() {
            let bookingId = $(this).data('id'); // Get booking ID
            let newStatus = $(this).data('status'); // New status (1 = Completed)
    
            Swal.fire({
                title: "Are you sure?",
                text: "You want to mark this booking as completed?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, complete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/admin/booking') }}/" + bookingId + "/update-status", // Laravel route
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}", // CSRF Token for security
                            status: newStatus
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire("Success!", "Booking marked as completed!", "success")
                                    .then(() => location.reload()); // Reload after confirmation
                            } else {
                                Swal.fire("Error!", "Error updating status. Try again.", "error");
                            }
                        },
                        error: function(xhr) {
                            console.error("Error:", xhr.responseText);
                            Swal.fire("Oops!", "Something went wrong.", "error");
                        }
                    });
                }
            });
        });
    </script>

<script>
    function printTable() {
        var table = $('#table-1').DataTable();

        // Destroy DataTable to remove responsive behavior
        table.destroy();

        // Reinitialize without responsive, disable paging and search
        var newTable = $('#table-1').DataTable({
            responsive: false,
            paging: false,
            searching: false
        });

        // Wait for reinitialization and redraw
        setTimeout(function() {
            var printContents = document.getElementById('table-1_wrapper').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;

            // Reload to restore original DataTable behavior
            location.reload();
        }, 500); // give it half a second to fully redraw
    }
</script>


    

@endsection

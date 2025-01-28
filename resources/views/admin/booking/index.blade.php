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
                                    <h4>Cars Inventory</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-primary mb-3" href="{{ route('car.create') }}">Create
                                </a>

                                <table class="responsive table " id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Car Id</th>
                                            <th>Customer Name</th>
                                            {{-- <th>Email Address</th> --}}
                                            <th>Phone Number</th>
                                            <th>Self Pickup</th>
                                            <th>Pickup Address</th>
                                            <th>Pickup Date</th>
                                            <th>Pickup Time</th>
                                            <th>Drop Off Address</th>
                                            <th>Drop Off Date</th>
                                            <th>Drop Off Time</th>
                                            <th>Additional Notes</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($drivers as $driver)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $driver->car_id }}</td>
                                                <td>{{ $driver->full_name }}</td>
                                                <td>{{ $driver->phone }}</td>

                                                {{-- <td>
                                                    @if ($driver->email)
                                                        <a href="mailto:{{ $driver->email }}">{{ $driver->email }}</a>
                                                    @endif
                                                </td> --}}
                                                
                                                <td>{{ $driver->self_pickup }}</td>
                                                {{-- <td>{{ $driver->durations }}</td> --}}
                                                {{-- <td>{{ $driver->call_number }}</td>
                                                <td>{{ $driver->whatsapp_number }}</td> --}}
                                                <td>{{ $driver->pickup_address }}</td>
                                                <td>{{ $driver->pickup_date }}</td>
                                                <td>{{ $driver->pickup_time }}</td>
                                                <td>{{ $driver->dropoff_address }}</td>
                                                <td>{{ $driver->dropoff_date }}</td>
                                                <td>{{ $driver->dropoff_time }}</td>
                                                <td>{{ $driver->driver_required }}</td>
                                                 {{-- <td>{!! $driver->car_play !!}</td> --}}
                                                 {{-- <td>
                                                    @if (!empty($driver->car_play))
                                                        @php
                                                            $features = explode("\n", $driver->car_play); // Assuming features are stored as a comma-separated string
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
                                            
                                                {{--<td>{{ $driver->delivery }}</td>
                                                <td>{{ $driver->pickup }}</td>
                                                <td>{{ $driver->travel_distance }}</td> --}} 
                                                {{-- <td>
                                                    <div class="badge {{ $driver->status == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
                                                        {{ $driver->status == 0 ? 'Activated' : 'Deactivated' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <img src="{{ asset($driver->image) }}" alt="" height="50"
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


                                                            {{-- <a href="{{ route('car.edit', $driver->id) }}"
                                                                class="btn btn-primary" style="margin-left: 10px">Edit</a> --}}
                                                            <form action="{{ route('car.destroy', $driver->id) }}"
                                                                method="POST"
                                                                style="display:inline-block; margin-left: 1px">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-flat show_confirm"
                                                                    data-toggle="tooltip">Delete</button>
                                                            </form>
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

   

@endsection

@section('js'
    <script>
        $(document).ready(function() {
            $('#table_id_events').DataTable();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>

    <script>
        $('.show_confirm').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Are you sure you want to delete this record?`,
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });

        function showDeactivationModal(managerId) {
            $('#deactivationForm').attr('action', '{{ url('admin/deactivate') }}/' + managerId);
            $('#deactivationModal').modal('show');
        }

        function showActivationModal(managerId) {
            $('#activationForm').attr('action', '{{ url('admin/activate') }}/' + managerId);
            $('#activationModal').modal('show');
        }
    </script>



@endsection

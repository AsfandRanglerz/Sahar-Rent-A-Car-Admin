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
                                @php
                                $isAdmin = $isAdmin ?? false;
                               $permissions = $subadminPermissions['cars_inventory'] ?? null;
                                // Fetch permissions for this menu
                               @endphp 
                           @if($isAdmin || ($permissions && $permissions->add == 1)) 
                                <a class="btn btn-primary mb-3" href="{{ route('car.create') }}">Create
                                </a>
                            @endif
                                <table class="responsive table " id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Car Id </th>
                                            <th>Car Name</th>
                                            {{-- <th>Availability</th> --}}
                                            <th>Price Per Hour (AED)</th>
                                            <th>Price Per Day (AED)</th>
                                            <th>Price Per Year (AED)</th>
                                            {{-- <th>Durations</th> --}}
                                            {{-- <th>Call Number</th>
                                            <th>Whatsapp Number</th> --}}
                                            <th>Passengers</th>
                                            <th>Luggage</th>
                                            <th>Doors</th>
                                            <th>Car Type</th>
                                            <th>Features</th>
                                            {{--<th>Car Feature</th>
                                            <th>Delivery</th>
                                            <th>PickUp</th>
                                            <th>Travel Distance</th> --}}
                                            <th>Status</th>
                                            <th>Image</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($CarDetails as $CarDetail)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $CarDetail->car_id }}</td>
                                                <td>{{ $CarDetail->car_name }}</td>

                                                {{-- <td>
                                                    @if ($CarDetail->email)
                                                        <a href="mailto:{{ $CarDetail->email }}">{{ $CarDetail->email }}</a>
                                                    @endif
                                                </td> --}}
                                                {{-- <td>{{ $CarDetail->availability }}</td> --}}
                                                <td>{{ $CarDetail->pricing }}</td>
                                                <td>{{ $CarDetail->sanitized }}</td>
                                                <td>{{ $CarDetail->car_feature }}</td>
                                                {{-- <td>{{ $CarDetail->durations }}</td> --}}
                                                {{-- <td>{{ $CarDetail->call_number }}</td>
                                                <td>{{ $CarDetail->whatsapp_number }}</td> --}}
                                                <td>{{ $CarDetail->passengers }}</td>
                                                <td>{{ $CarDetail->luggage }}</td>
                                                <td>{{ $CarDetail->doors }}</td>
                                                <td>{{ $CarDetail->car_type }}</td>
                                                 {{-- <td>{!! $CarDetail->car_play !!}</td> --}}
                                                 <td>
                                                    @if (!empty($CarDetail->car_play))
                                                        @php
                                                            $features = explode("\n", $CarDetail->car_play); // Assuming features are stored as a comma-separated string
                                                        @endphp
                                                        <ul>
                                                            @foreach ($features as $feature)
                                                                <li>{{ trim($feature) }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            
                                                {{--<td>{{ $CarDetail->delivery }}</td>
                                                <td>{{ $CarDetail->pickup }}</td>
                                                <td>{{ $CarDetail->travel_distance }}</td> --}} 
                                                <td>
                                                    <div class="badge {{ $CarDetail->status == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
                                                        {{ $CarDetail->status == 0 ? 'Activated' : 'Deactivated' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($CarDetail->image)
                                                    <img src="{{ asset($CarDetail->image) }}" alt="" height="50"
                                                        width="50" class="image">
                                                    @else
                                                    <span>No Image</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <div class="d-flex gap-4">
                                                        <div class="gap-3"
                                                            style="display: flex; align-items: center; justify-content: center; column-gap: 8px">



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
                                                            <a href="{{ route('car.edit', $CarDetail->id) }}"
                                                                class="btn btn-primary" style="margin-left: 10px">Edit</a>
                                                            @endif
                                                            @if($isAdmin || ($permissions && $permissions->delete == 1))    
                                                                <form action="{{ route('car.destroy', $CarDetail->id) }}"
                                                                method="POST"
                                                                style="display:inline-block; margin-left: 10px">
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

    <!-- Deactivation Modal -->
    <div class="modal fade" id="deactivationModal" tabindex="-1" role="dialog" aria-labelledby="deactivationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="deactivationForm" action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="deactivationModalLabel">Reason for Deactivation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason">Please provide the reason for deactivating this Store Manager:</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>

                        </div>
                    </div>
                    <input type="hidden" id="status" name="status" value="0">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Deactivate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Activation Modal -->
    <div class="modal fade" id="activationModal" tabindex="-1" role="dialog" aria-labelledby="activationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="activationForm" action="" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="activationModalLabel">Are you sure you want to activate this Store
                            Manager?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <input type="hidden" id="status" name="status" value="1">


                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Activate</button>
                    </div>
                </form>
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
        $(document).on('click', '.show_confirm', function(event) {
    event.preventDefault(); // Prevent default form submission
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

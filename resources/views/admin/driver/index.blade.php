@extends('admin.layout.app')
@section('title', 'Users')
@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Drivers</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                {{-- @php
                                 $isAdmin = $isAdmin ?? false;
    $permissions = $subadminPermissions['drivers'] ?? null;
    // Fetch permissions for this menu
@endphp --}}
{{-- @if($isAdmin || ($permissions && $permissions->add == 1)) --}}
                                <a class="btn btn-primary mb-3" href="{{ route('driver.create') }}">Create</a>
{{-- @endif --}}
                                <table class="responsive table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Document (License)</th>
                                            <th>Image</th>
                                            <th>Availability</th>
                                            {{-- <th>Status</th> --}}
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($drivers as $driver)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $driver->name }}</td>
                                                <td>
                                                    @if ($driver->email)
                                                        <a href="mailto:{{ $driver->email }}">{{ $driver->email }}</a>
                                                    @endif
                                                </td>
                                                <td>{{ $driver->phone }}</td>
                                                {{-- <td>
                                                    @if ($driver->driverdocument)
                                                        <ul>
                                                            @if ($driver->driverdocument->license)
                                                                <li>
                                                                    <a href="{{ asset('public/storage/' . $driver->driverdocument->license) }}" target="_blank">License</a>
                                                                </li>
                                                            @endif
                                                           
                                                        </ul>
                                                    @else
                                                        <span>No documents uploaded</span>
                                                    @endif
                                                </td> --}}
                                                <td>
                                                    @if ($driver->license || ($driver->driverdocument && $driver->driverdocument->license))
                                                        @php
                                                            $documents = [];
                                                
                                                            if ($driver->license) {
                                                                $documents[] = [
                                                                    'name' => 'License',
                                                                    'url' => asset('storage/app/public/' . $driver->license),
                                                                ];
                                                            }
                                                
                                                            if ($driver->driverdocument && $driver->driverdocument->license) {
                                                                $documents[] = [
                                                                    'name' => 'License',
                                                                    'url' => asset('storage/app/public/' . $driver->driverdocument->license),
                                                                ];
                                                            }
                                                        @endphp
                                                
                                                        @if (count($documents) === 1)
                                                            <a href="{{ $documents[0]['url'] }}" target="_blank">{{ $documents[0]['name'] }}</a>
                                                        @elseif (count($documents) > 1)
                                                            <ul>
                                                                @foreach ($documents as $document)
                                                                    <li>
                                                                        <a href="{{ $document['url'] }}" target="_blank">{{ $document['name'] }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    @else
                                                        <span>No documents uploaded</span>
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    @if($driver->image)
                                                    <img src="{{ asset($driver->image) }}" alt="" height="50"
                                                        width="50" class="image">
                                                        @else
                                                        <span>No Image</span>
                                                        @endif
                                                </td>
                                                <td>
                                                    <div class="badge {{ $driver->availability == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
                                                        {{ $driver->availability == 0 ? 'Available' : 'Not Available' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-4">
                                                        <div class="gap-3"
                                                            style="display: flex; align-items: center; justify-content: center; column-gap: 8px">
                                                            @if ($driver->status == 1)
                                                                <a href="javascript:void(0);"
                                                                    onclick="showDeactivationModal({{ $driver->id }})"
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
                                                            @elseif($driver->status == 0)
                                                                <a href="javascript:void(0);"
                                                                    onclick="showActivationModal({{ $driver->id }})"
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
                                                            @endif
                                                            {{-- @if($isAdmin || ($permissions && $permissions->edit == 1)) --}}
                                                            <a href="{{ route('driver.edit', $driver->id) }}"
                                                                class="btn btn-primary" style="margin-left: 10px">Edit</a>
                                                            {{-- @endif --}}
                                                            {{-- @if($isAdmin || ($permissions && $permissions->delete == 1))     --}}
                                                            <form action="{{ route('driver.destroy', $driver->id) }}"
                                                                method="POST"
                                                                style="display:inline-block; margin-left: 10px">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-flat show_confirm"
                                                                    data-toggle="tooltip">Delete</button>
                                                            </form>
                                                            {{-- @endif --}}
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
                            <label for="reason">Please provide the reason for deactivating this driver:</label>
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
                        <h5 class="modal-title" id="activationModalLabel">Are you sure you want to activate this driver?</h5>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
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
            $('#deactivationForm').attr('action', '{{ url('admin/driverDeactivate') }}/' + managerId);
            $('#deactivationModal').modal('show');
        }

        function showActivationModal(managerId) {
            $('#activationForm').attr('action', '{{ url('admin/driverActivate') }}/' + managerId);
            $('#activationModal').modal('show');
        }

    </script>
@endsection

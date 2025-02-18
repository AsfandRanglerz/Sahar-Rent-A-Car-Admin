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
                                    <h4>License Approvals</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                {{-- @php
                                $isAdmin = $isAdmin ?? false;
                               $permissions = $subadminPermissions['license_approvals'] ?? null;
                                // Fetch permissions for this menu
                               @endphp 
                           @if($isAdmin || ($permissions && $permissions->add == 1))  --}}
                                {{--<a class="btn btn-primary mb-3" href="{{ route('license.create') }}">Create</a>--}}
{{-- @endif --}}
                                <table class="responsive table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            {{-- <th>Phone</th> --}}
                                            <th>License</th>
                                            {{-- <th>Availability</th> --}}
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($LicenseApprovals as $LicenseApproval)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $LicenseApproval->driver ? $LicenseApproval->driver->name : 'N/A' }}</td>
                                                <td>
                                                    {{-- @if ($LicenseApproval->driver && $LicenseApproval->driver->email)
                                                        <a href="mailto:{{ $LicenseApproval->driver->email }}">{{ $LicenseApproval->driver->email }}</a>
                                                    @endif --}}
                                                    @if ($LicenseApproval->driver && $LicenseApproval->driver->email)
                                                    <a href="mailto:{{ $LicenseApproval->driver->email }}">{{ $LicenseApproval->driver->email }}</a>
                                                @else
                                                    N/A
                                                @endif
                                                </td>
                                                {{-- <td>{{ $LicenseApproval->phone }}</td> --}}
                                                <td>
                                                    {{-- <img src="{{ asset($LicenseApproval->image) }}" alt="" height="45"
                                                        width="50" class="image" style="cursor: pointer;" data-toggle="modal" data-target="#imageModal" data-image="{{ asset($LicenseApproval->image) }}"> --}}
                                                        @if ($LicenseApproval->driver && $LicenseApproval->driver->document && $LicenseApproval->driver->document->license)
                                                        <img src="{{ asset('storage/app/public/' . $LicenseApproval->driver->document->license) }}" 
                                                            alt="License Image" height="45" width="50" class="image"
                                                            style="cursor: pointer;" data-toggle="modal" 
                                                            data-target="#imageModal" 
                                                            data-image="{{ asset('storage/app/public/' . $LicenseApproval->driver->document->license) }}">
                                                    @else
                                                        N/A
                                                    @endif
                                                    </td>
                                                {{-- <td>
                                                    <div class="badge {{ $LicenseApproval->status == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
                                                        {{ $LicenseApproval->status == 0 ? 'Accepted' : 'Rejected' }}
                                                    </div>
                                                </td> --}}
                                                <td>
                                                    {{-- <div class="badge 
                                                        {{ $LicenseApproval->action == 1 ? 'badge-success' : ($LicenseApproval->action == 0 ? 'badge-danger' : 'badge-warning') }} 
                                                        badge-shadow">
                                                        {{ $LicenseApproval->action == 1 ? 'Approved' : ($LicenseApproval->action == 0 ? 'Rejected' : 'Pending') }}
                                                    </div> --}}
                                                    @if ($LicenseApproval->status == 1)
                                                        <div class="badge badge-success badge-shadow">Approved</div>
                                                    @elseif($LicenseApproval->status == 0)
                                                        <div class="badge badge-danger badge-shadow">Rejected</div>
                                                    @elseif($LicenseApproval->status == 2)
                                                        <div class="badge badge-warning badge-shadow">Pending</div>
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    <div class="d-flex gap-4">
                                                        <div class="gap-3" style="display: flex; align-items: center; justify-content: center; column-gap: 8px">
                                                            <!-- Approve Button -->
                                                            <a href="javascript:void(0);" onclick="showActivationModal({{ $LicenseApproval->id }})"
                                                                class="btn btn-success">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-left">
                                                                    <rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect>
                                                                    <circle cx="16" cy="12" r="3"></circle>
                                                                </svg>
                                                                
                                                            </a>
                                                    
                                                            <!-- Reject Button -->
                                                            <a href="javascript:void(0);" onclick="showDeactivationModal({{ $LicenseApproval->id }})"
                                                                class="btn btn-danger">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-toggle-left">
                                                                    <rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect>
                                                                    <circle cx="16" cy="12" r="3"></circle>
                                                                </svg>
                                                                
                                                            </a>
                                                            {{-- @if($isAdmin || ($permissions && $permissions->edit == 1)) --}}
                                                            {{-- <a href="{{ route('license.edit', $LicenseApproval->id) }}"
                                                                class="btn btn-primary" style="margin-left: 10px">Edit</a> --}}
                                                                {{-- @endif --}}
                                                                @if($isAdmin || ($permissions && $permissions->delete == 1))    
                                                                <form action="{{ route('license.destroy', $LicenseApproval->id) }}"
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
                        <h5 class="modal-title" id="deactivationModalLabel">Reason for Rejection</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="reason">Please provide the reason for rejection:</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>

                        </div>
                    </div>
                    <input type="hidden" id="action" name="action" value="0">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Reject</button>
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
                        <h5 class="modal-title" id="activationModalLabel">Approve this license?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <input type="hidden" id="action" name="action" value="1">


                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="imageModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-transparent shadow-none">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-transparent">
                    <img id="modalImage" src="" alt="" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
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
            $('#deactivationForm').attr('action', '{{ url('admin/LicenseApprovalDeactivate') }}/' + managerId);
            $('#deactivationModal').modal('show');
        }

        function showActivationModal(managerId) {
            $('#activationForm').attr('action', '{{ url('admin/LicenseApprovalActivate') }}/' + managerId);
            $('#activationModal').modal('show');
        }

        $(document).ready(function() {
            $('#imageModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var imageUrl = button.data('image');
                var modal = $(this);
                modal.find('#modalImage').attr('src', imageUrl);
            });
        });
    </script>
@endsection

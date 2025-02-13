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
                                    <h4>Loyalty Points</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                @php
                                $isAdmin = $isAdmin ?? false;
                               $permissions = $subadminPermissions['loyalty_points'] ?? null;
                                // Fetch permissions for this menu
                               @endphp 
                           @if($isAdmin || ($permissions && $permissions->add == 1)) 
                                <a class="btn btn-primary mb-3" href="{{ route('loyaltypoints.create') }}">Create</a>
@endif
                                <table class="responsive table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Referal Link</th>
                                            <th>Car Rental</th>
                                            {{-- <th>Phone</th> --}}
                                            <th>Discount</th>
                                            {{-- <th>Availability</th> --}}
                                            {{-- <th>Status</th> --}}
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($loyaltypoints as $loyaltypoint)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $loyaltypoint->on_referal }}</td>
                                                {{-- <td>
                                                    @if ($loyaltypoint->email)
                                                        <a href="mailto:{{ $loyaltypoint->email }}">{{ $loyaltypoint->email }}</a>
                                                    @endif
                                                </td> --}}
                                                <td>{{ $loyaltypoint->on_car }}</td>
                                                <td>{{ $loyaltypoint->discount }}%</td>
                                                {{-- <td>
                                                    <img src="{{ asset($loyaltypoint->image) }}" alt="" height="50"
                                                        width="50" class="image">
                                                </td> --}}
                                                {{-- <td>
                                                    <div class="badge {{ $loyaltypoint->status == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
                                                        {{ $loyaltypoint->status == 0 ? 'Accepted' : 'Rejected' }}
                                                    </div>
                                                </td> --}}
                                                {{-- <td>
                                                    <div class="badge 
                                                        {{ $loyaltypoint->status == 0 ? 'badge-success' : ($loyaltypoint->status == 1 ? 'badge-danger' : 'badge-warning') }} 
                                                        badge-shadow">
                                                        {{ $loyaltypoint->status == 0 ? 'Accepted' : ($loyaltypoint->status == 1 ? 'Rejected' : 'Pending') }}
                                                    </div>
                                                </td> --}}
                                                
                                                <td>
                                                    <div class="d-flex gap-4">
                                                        <div class="gap-3"
                                                            style="display: flex; align-items: center; justify-content: center; column-gap: 8px">
                                                            {{-- @if ($loyaltypoint->action == 1)
                                                                <a href="javascript:void(0);"
                                                                    onclick="showDeactivationModal({{ $loyaltypoint->id }})"
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
                                                            @elseif($loyaltypoint->action == 0)
                                                                <a href="javascript:void(0);"
                                                                    onclick="showActivationModal({{ $loyaltypoint->id }})"
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
                                                            <a href="{{ route('loyaltypoints.edit', $loyaltypoint->id) }}"
                                                                class="btn btn-primary" style="margin-left: 10px">Edit</a>
                                                            @endif
                                                            @if($isAdmin || ($permissions && $permissions->delete == 1)) 
                                                                <form action="{{ route('loyaltypoints.destroy', $loyaltypoint->id) }}"
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
    {{-- <div class="modal fade" id="deactivationModal" tabindex="-1" role="dialog" aria-labelledby="deactivationModalLabel"
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
    </div> --}}

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

    </script>
@endsection

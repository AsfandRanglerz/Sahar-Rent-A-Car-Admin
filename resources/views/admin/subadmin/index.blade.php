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
                                    <h4>Sub Admins</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-primary mb-3" href="{{ route('subadmin.create') }}">Create</a>

                                <table class="responsive table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Image</th>
                                            <th>Permission</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subadmins as $subadmin)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $subadmin->name }}</td>
                                                <td>
                                                    @if ($subadmin->email)
                                                        <a href="mailto:{{ $subadmin->email }}">{{ $subadmin->email }}</a>
                                                    @endif
                                                </td>
                                                <td>{{ $subadmin->phone }}</td>
                                                <td>
                                                    @if($subadmin->image)
                                                    <img src="{{ asset($subadmin->image) }}" alt="" height="50" width="50" class="image">
                                                @else
                                                <span>No Image</span>
                                                @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info view-permissions" data-subadmin-id="{{ $subadmin->id }}">
                                                        View
                                                    </button>
                                                </td>
                                                
                                                <td>
                                                    <div class="d-flex gap-4">
                                                        <div class="gap-3"
                                                            style="display: flex; align-items: center; justify-content: center; column-gap: 8px">

                                                            @if ($subadmin->status == 1)
                                                                <a href="javascript:void(0);"
                                                                    onclick="showDeactivationModal({{ $subadmin->id }})"
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
                                                            @elseif($subadmin->status == 0)
                                                                <a href="javascript:void(0);"
                                                                    onclick="showActivationModal({{ $subadmin->id }})"
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


                                                            <a href="{{ route('subadmin.edit', $subadmin->id) }}"
                                                                class="btn btn-primary" style="margin-left: 10px">Edit</a>
                                                            <form action="{{ route('subadmin.destroy', $subadmin->id) }}"
                                                                method="POST"
                                                                style="display:inline-block; margin-left: 10px">
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
<!-- Permissions Modal -->
<div class="modal fade" id="permissionsModal" tabindex="-1" role="dialog" aria-labelledby="permissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Permissions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="permissionsForm">
                    <input type="hidden" name="subadmin_id" id="subadmin_id">
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Side Menus</th>
                                <th>Add</th>
                                <th>Edit</th>
                                <th>View</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dashboard</td>
                                <td><input type="checkbox" name="permissions[dashboard][add]" ></td>
                                <td><input type="checkbox" name="permissions[dashboard][edit]" ></td>
                                <td><input type="checkbox" name="permissions[dashboard][view]"></td>
                                <td><input type="checkbox" name="permissions[dashboard][delete]" ></td>
                            </tr>
                            <tr>
                                <td>Sub Admins</td>
                                <td><input type="checkbox" name="permissions[sub_admins][add]></td>
                                <td><input type="checkbox" name="permissions[sub_admins][edit]"></td>
                                <td><input type="checkbox" name="permissions[sub_admins][view]"></td>
                                <td><input type="checkbox" name="permissions[sub_admins][delete]" ></td>
                            </tr>
                            <tr>
                                <td>Customers</td>
                                <td><input type="checkbox" name="permissions[customers][add]"></td>
                                <td><input type="checkbox" name="permissions[customers][edit]"></td>
                                <td><input type="checkbox" name="permissions[customers][view]"></td>
                                <td><input type="checkbox" name="permissions[customers][delete]" ></td>
                            </tr>
                            <tr>
                                <td>Drivers</td>
                                <td><input type="checkbox" name="permissions[drivers][add]"></td>
                                <td><input type="checkbox" name="permissions[drivers][edit]"></td>
                                <td><input type="checkbox" name="permissions[drivers][view]"></td>
                                <td><input type="checkbox" name="permissions[drivers][delete]"></td>
                            </tr>
                            <tr>
                                <td>Cars Inventory</td>
                                <td><input type="checkbox" name="permissions[cars_inventory][add]" ></td>
                                <td><input type="checkbox" name="permissions[cars_inventory][edit]"  ></td>
                                <td><input type="checkbox" name="permissions[cars_inventory][view]"  ></td>
                                <td><input type="checkbox" name="permissions[cars_inventory][delete]"  ></td>
                            </tr>
                            <tr>
                                <td>License Approvals</td>
                                <td><input type="checkbox" name="permissions[license_approvals][add]"  ></td>
                                <td><input type="checkbox" name="permissions[license_approvals][edit]"  ></td>
                                <td><input type="checkbox" name="permissions[license_approvals][view]"  ></td>
                                <td><input type="checkbox" name="permissions[license_approvals][delete]"  ></td>
                            </tr>
                            <tr>
                                <td>Notifications</td>
                                <td><input type="checkbox" name="permissions[notifications][add]" ></td>
                                <td><input type="checkbox" name="permissions[notifications][edit]" ></td>
                                <td><input type="checkbox" name="permissions[notifications][view]" ></td>
                                <td><input type="checkbox" name="permissions[notifications][delete]" ></td>
                            </tr>
                            <tr>
                                <td>Bookings</td>
                                <td><input type="checkbox" name="permissions[bookings][ad></td>
                                <td><input type="checkbox" name="permissions[bookings][edit></td>
                                <td><input type="checkbox" name="permissions[bookings][view></td>
                                <td><input type="checkbox" name="permissions[bookings][delete]"></td>
                            </tr>
                            <tr>
                                <td>Loyalty Points</td>
                                <td><input type="checkbox" name="permissions[loyalty_points][add]" ></td>
                                <td><input type="checkbox" name="permissions[loyalty_points][edit]"  ></td>
                                <td><input type="checkbox" name="permissions[loyalty_points][view]"  ></td>
                                <td><input type="checkbox" name="permissions[loyalty_points][delete]"  ></td>
                            </tr>
                            <tr>
                                <td>Privacy Policy</td>
                                <td><input type="checkbox" name="permissions[privacy_policy][add]" ></td>
                                <td><input type="checkbox" name="permissions[privacy_policy][edit]"  ></td>
                                <td><input type="checkbox" name="permissions[privacy_policy][view]"  ></td>
                                <td><input type="checkbox" name="permissions[privacy_policy][delete]"  ></td>
                            </tr>
                            <tr>
                                <td>Terms & Conditions</td>
                                <td><input type="checkbox" name="permissions[terms_conditions][add]" ></td>
                                <td><input type="checkbox" name="permissions[terms_conditions][edit]" ></td>
                                <td><input type="checkbox" name="permissions[terms_conditions][view]" ></td>
                                <td><input type="checkbox" name="permissions[terms_conditions][delete]" ></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Permissions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
            $('#deactivationForm').attr('action', '{{ url('admin/subadminDeactivate') }}/' + managerId);
            $('#deactivationModal').modal('show');
        }

        function showActivationModal(managerId) {
            $('#activationForm').attr('action', '{{ url('admin/subadminActivate') }}/' + managerId);
            $('#activationModal').modal('show');
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Show Permissions Modal when clicking "View" button
            $(".view-permissions").click(function() {
                let subadminId = $(this).data('subadmin-id');
                $("#subadmin_id").val(subadminId); // Set the subadmin ID in the hidden field
                
                $("#permissionsModal").modal("show"); // Show the modal
            });
    
            // Submit Permissions Form via AJAX
            $("#permissionsForm").submit(function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
    
                $.ajax({
                    url: "{{ route('subadmin.savePermissions') }}",
                    type: "POST",
                    data: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function(response) {
                        alert(response.message);
                        $("#permissionsModal").modal("hide");
                    },
                    error: function() {
                        alert("Error saving permissions.");
                    }
                });
            });
        });
    </script>
@endsection

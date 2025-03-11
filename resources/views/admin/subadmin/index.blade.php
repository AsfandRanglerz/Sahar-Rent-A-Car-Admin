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
                            @php
                            $isAdmin = $isAdmin ?? false;
                           $permissions = $subadminPermissions['sub_admins'] ?? null;
// Fetch permissions for this menu
                           @endphp
                            <div class="card-body table-striped table-bordered table-responsive">
                                @if($isAdmin || ($permissions && $permissions->add == 1))
                                <a class="btn btn-primary mb-3" href="{{ route('subadmin.create') }}">Create</a>
@endif
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

                                                            @if($isAdmin || ($permissions && $permissions->edit == 1))
                                                            <a href="{{ route('subadmin.edit', $subadmin->id) }}"
                                                                class="btn btn-primary" style="margin-left: 10px">Edit</a>
                                                                @endif
                                                                @if($isAdmin || ($permissions && $permissions->delete == 1))
                                                            <form action="{{ route('subadmin.destroy', $subadmin->id) }}"
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
                                <th colspan="4" class="text-center">Permissions</th>
                                {{-- <th>Add</th>
                                <th>Edit</th>
                                <th>View</th>
                                <th>Delete</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Dashboard</td>
                                {{-- <td><label>Add <input type="checkbox" name="permissions[dashboard][add]" ></label></td>
                                <td><label>Edit <input type="checkbox" name="permissions[dashboard][edit]" ></label></td>
                                <td><label>View <input type="checkbox" name="permissions[dashboard][view]"></label></td>
                                <td><label>Delete <input type="checkbox" name="permissions[dashboard][delete]" ></label></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[dashboard][add]" id="dashboardAdd">
                                        <label class="form-check-label" for="dashboardAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[dashboard][edit]" id="dashboardEdit">
                                        <label class="form-check-label" for="dashboardEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[dashboard][view]" id="dashboardView">
                                        <label class="form-check-label" for="dashboardView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[dashboard][delete]" id="dashboardDelete">
                                        <label class="form-check-label" for="dashboardDelete">Delete</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Sub Admins</td>
                                {{-- <td><input type="checkbox" name="permissions[sub_admins][add]"></td>
                                <td><input type="checkbox" name="permissions[sub_admins][edit]"></td>
                                <td><input type="checkbox" name="permissions[sub_admins][view]"></td>
                                <td><input type="checkbox" name="permissions[sub_admins][delete]" ></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[sub_admins][add]" id="sub_adminsAdd">
                                        <label class="form-check-label" for="sub_adminsAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[sub_admins][edit]" id="sub_adminsEdit">
                                        <label class="form-check-label" for="sub_adminsEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[sub_admins][view]" id="sub_adminsView">
                                        <label class="form-check-label" for="sub_adminsView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[sub_admins][delete]" id="sub_adminsDelete">
                                        <label class="form-check-label" for="sub_adminsDelete">Delete</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Customers</td>
                                {{-- <td><input type="checkbox" name="permissions[customers][add]"></td>
                                <td><input type="checkbox" name="permissions[customers][edit]"></td>
                                <td><input type="checkbox" name="permissions[customers][view]"></td>
                                <td><input type="checkbox" name="permissions[customers][delete]" ></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[customers][add]" id="customersAdd">
                                        <label class="form-check-label" for="customersAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[customers][edit]" id="customersEdit">
                                        <label class="form-check-label" for="customersEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[customers][view]" id="customersView">
                                        <label class="form-check-label" for="customersView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[customers][delete]" id="customersDelete">
                                        <label class="form-check-label" for="customersDelete">Delete</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Drivers</td>
                                {{-- <td><input type="checkbox" name="permissions[drivers][add]"></td>
                                <td><input type="checkbox" name="permissions[drivers][edit]"></td>
                                <td><input type="checkbox" name="permissions[drivers][view]"></td>
                                <td><input type="checkbox" name="permissions[drivers][delete]"></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[drivers][add]" id="driversAdd">
                                        <label class="form-check-label" for="driversAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[drivers][edit]" id="driversEdit">
                                        <label class="form-check-label" for="driversEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[drivers][view]" id="driversView">
                                        <label class="form-check-label" for="driversView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[drivers][delete]" id="driversDelete">
                                        <label class="form-check-label" for="driversDelete">Delete</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Cars Inventory</td>
                                {{-- <td><input type="checkbox" name="permissions[cars_inventory][add]" ></td>
                                <td><input type="checkbox" name="permissions[cars_inventory][edit]"  ></td>
                                <td><input type="checkbox" name="permissions[cars_inventory][view]"  ></td>
                                <td><input type="checkbox" name="permissions[cars_inventory][delete]"  ></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[cars_inventory][add]" id="cars_inventoryAdd">
                                        <label class="form-check-label" for="cars_inventoryAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[cars_inventory][edit]" id="cars_inventoryEdit">
                                        <label class="form-check-label" for="cars_inventoryEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[cars_inventory][view]" id="cars_inventoryView">
                                        <label class="form-check-label" for="cars_inventoryView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[cars_inventory][delete]" id="cars_inventoryDelete">
                                        <label class="form-check-label" for="cars_inventoryDelete">Delete</label>
                                    </div>
                            </tr>
                            <tr>
                                <td>License Approvals</td>
                                {{-- <td><input type="checkbox" name="permissions[license_approvals][add]"  ></td>
                                <td><input type="checkbox" name="permissions[license_approvals][edit]"  ></td>
                                <td><input type="checkbox" name="permissions[license_approvals][view]"  ></td>
                                <td><input type="checkbox" name="permissions[license_approvals][delete]"  ></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[license_approvals][add]" id="license_approvalsAdd">
                                        <label class="form-check-label" for="license_approvalsAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[license_approvals][edit]" id="license_approvalsEdit">
                                        <label class="form-check-label" for="license_approvalsEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[license_approvals][view]" id="license_approvalsView">
                                        <label class="form-check-label" for="license_approvalsView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[license_approvals][delete]" id="license_approvalsDelete">
                                        <label class="form-check-label" for="license_approvalsDelete">Delete</label>
                                    </div>
                            </tr>
                            <tr>
                                <td>Notifications</td>
                                {{-- <td><input type="checkbox" name="permissions[notifications][add]" ></td>
                                <td><input type="checkbox" name="permissions[notifications][edit]" ></td>
                                <td><input type="checkbox" name="permissions[notifications][view]" ></td>
                                <td><input type="checkbox" name="permissions[notifications][delete]" ></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[notifications][add]" id="notificationsAdd">
                                        <label class="form-check-label" for="notificationsAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[notifications][edit]" id="notificationsEdit">
                                        <label class="form-check-label" for="notificationsEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[notifications][view]" id="notificationsView">
                                        <label class="form-check-label" for="notificationsView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[notifications][delete]" id="notificationsDelete">
                                        <label class="form-check-label" for="notificationsDelete">Delete</label>
                                    </div>
                            </tr>
                            <tr>
                                <td>Bookings</td>
                                {{-- <td><input type="checkbox" name="permissions[bookings][add]"></td>
                                <td><input type="checkbox" name="permissions[bookings][edit]"></td>
                                <td><input type="checkbox" name="permissions[bookings][view]"></td>
                                <td><input type="checkbox" name="permissions[bookings][delete]"></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[bookings][add]" id="bookingsAdd">
                                        <label class="form-check-label" for="bookingsAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[bookings][edit]" id="bookingsEdit">
                                        <label class="form-check-label" for="bookingsEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[bookings][view]" id="bookingsView">
                                        <label class="form-check-label" for="bookingsView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[bookings][delete]" id="bookingsDelete">
                                        <label class="form-check-label" for="bookingsDelete">Delete</label>
                                    </div>
                            </tr>
                            <tr>
                                <td>Request Bookings</td>
                                {{-- <td><input type="checkbox" name="permissions[requestbookings][add]"></td>
                                <td><input type="checkbox" name="permissions[requestbookings][edit]"></td>
                                <td><input type="checkbox" name="permissions[requestbookings][view]"></td>
                                <td><input type="checkbox" name="permissions[requestbookings][delete]"></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[requestbookings][add]" id="requestbookingsAdd">
                                        <label class="form-check-label" for="requestbookingsAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[requestbookings][edit]" id="requestbookingsEdit">
                                        <label class="form-check-label" for="requestbookingsEdit">Assign</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[requestbookings][view]" id="requestbookingsView">
                                        <label class="form-check-label" for="requestbookingsView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[requestbookings][delete]" id="requestbookingsDelete">
                                        <label class="form-check-label" for="requestbookingsDelete">Delete</label>
                                    </div>
                            </tr>
                            <tr>
                                <td>Car Rental Points</td>
                                {{-- <td><input type="checkbox" name="permissions[loyalty_points][add]" ></td>
                                <td><input type="checkbox" name="permissions[loyalty_points][edit]"  ></td>
                                <td><input type="checkbox" name="permissions[loyalty_points][view]"  ></td>
                                <td><input type="checkbox" name="permissions[loyalty_points][delete]"  ></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[loyalty_points][add]" id="loyalty_pointsAdd">
                                        <label class="form-check-label" for="loyalty_pointsAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[loyalty_points][edit]" id="loyalty_pointsEdit">
                                        <label class="form-check-label" for="loyalty_pointsEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[loyalty_points][view]" id="loyalty_pointsView">
                                        <label class="form-check-label" for="loyalty_pointsView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[loyalty_points][delete]" id="loyalty_pointsDelete">
                                        <label class="form-check-label" for="loyalty_pointsDelete">Delete</label>
                                    </div>
                            </tr>
                            <tr>
                                <td>Referal Link Points</td>
                                {{-- <td><input type="checkbox" name="permissions[referal_links][add]" ></td>
                                <td><input type="checkbox" name="permissions[referal_links][edit]"  ></td>
                                <td><input type="checkbox" name="permissions[referal_links][view]"  ></td>
                                <td><input type="checkbox" name="permissions[referal_links][delete]"  ></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[referal_links][add]" id="referal_linksAdd">
                                        <label class="form-check-label" for="referal_linksAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[referal_links][edit]" id="referal_linksEdit">
                                        <label class="form-check-label" for="referal_linksEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[referal_links][view]" id="referal_linksView">
                                        <label class="form-check-label" for="referal_linksView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[referal_links][delete]" id="referal_linksDelete">
                                        <label class="form-check-label" for="referal_linksDelete">Delete</label>
                                    </div>
                            </tr>
                            <tr>
                                <td>Contact Us</td>
                                {{-- <td><input type="checkbox" name="permissions[ContactUs][add]" ></td>
                                <td><input type="checkbox" name="permissions[ContactUs][edit]"  ></td>
                                <td><input type="checkbox" name="permissions[ContactUs][view]"  ></td>
                                <td><input type="checkbox" name="permissions[ContactUs][delete]"  ></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[ContactUs][add]" id="ContactUsAdd">
                                        <label class="form-check-label" for="ContactUsAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[ContactUs][edit]" id="ContactUsEdit">
                                        <label class="form-check-label" for="ContactUsEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[ContactUs][view]" id="ContactUsView">
                                        <label class="form-check-label" for="ContactUsView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[ContactUs][delete]" id="ContactUsDelete">
                                        <label class="form-check-label" for="ContactUsDelete">Delete</label>
                                    </div>
                            </tr>
                            <tr>
                                <td>Privacy Policy</td>
                                {{-- <td><input type="checkbox" name="permissions[privacy_policy][add]" ></td>
                                <td><input type="checkbox" name="permissions[privacy_policy][edit]"  ></td>
                                <td><input type="checkbox" name="permissions[privacy_policy][view]"  ></td>
                                <td><input type="checkbox" name="permissions[privacy_policy][delete]"  ></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[privacy_policy][add]" id="privacy_policyAdd">
                                        <label class="form-check-label" for="privacy_policyAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[privacy_policy][edit]" id="privacy_policyEdit">
                                        <label class="form-check-label" for="privacy_policyEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[privacy_policy][view]" id="privacy_policyView">
                                        <label class="form-check-label" for="privacy_policyView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[privacy_policy][delete]" id="privacy_policyDelete">
                                        <label class="form-check-label" for="privacy_policyDelete">Delete</label>
                                    </div>
                            </tr>
                            <tr>
                                <td>Terms & Conditions</td>
                                {{-- <td><input type="checkbox" name="permissions[terms_conditions][add]" ></td>
                                <td><input type="checkbox" name="permissions[terms_conditions][edit]" ></td>
                                <td><input type="checkbox" name="permissions[terms_conditions][view]" ></td>
                                <td><input type="checkbox" name="permissions[terms_conditions][delete]" ></td> --}}
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[terms_conditions][add]" id="terms_conditionsAdd">
                                        <label class="form-check-label" for="terms_conditionsAdd">Add</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[terms_conditions][edit]" id="terms_conditionsEdit">
                                        <label class="form-check-label" for="terms_conditionsEdit">Edit</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[terms_conditions][view]" id="terms_conditionsView">
                                        <label class="form-check-label" for="terms_conditionsView">View</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-1" type="checkbox" name="permissions[terms_conditions][delete]" id="terms_conditionsDelete">
                                        <label class="form-check-label" for="terms_conditionsDelete">Delete</label>
                                    </div>
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
                          <label for="reason">Please provide the reason for deactivating this SubAdmin:</label>
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
                      <h5 class="modal-title" id="activationModalLabel">Are you sure you want to activate this SubAdmin?</h5>
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
        // $(document).ready(function() {
        //     // Show Permissions Modal when clicking "View" button
        //     $(".view-permissions").click(function() {
        //         let subadminId = $(this).data('subadmin-id');
        //         $("#subadmin_id").val(subadminId); // Set the subadmin ID in the hidden field
                
        //         $("#permissionsModal").modal("show"); // Show the modal
        //     });
    
        //     // Submit Permissions Form via AJAX
        //     $("#permissionsForm").submit(function(e) {
        //         e.preventDefault();
        //         let formData = $(this).serialize();
    
        //         $.ajax({
        //             url: "{{ route('subadmin.savePermissions') }}",
        //             type: "POST",
        //             data: formData,
        //             headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        //             success: function(response) {
        //                 alert(response.message);
        //                 $("#permissionsModal").modal("hide");
        //             },
        //             error: function() {
        //                 alert("Error saving permissions.");
        //             }
        //         });
        //     });
        // });

        $(document).ready(function() {
    // Show Permissions Modal when clicking "View" button
    $(".view-permissions").click(function() {
        let subadminId = $(this).data('subadmin-id');
        $("#subadmin_id").val(subadminId); // Set the subadmin ID in the hidden field

        // Fetch Permissions from Database
        $.ajax({
            url: "{{ route('subadmin.getPermissions') }}",
            type: "GET",
            data: { subadmin_id: subadminId },
            success: function(response) {
                console.log(response); // Debugging: Ensure response contains expected data

                // Clear all checkboxes first
                $("input[type='checkbox']").prop("checked", false);

                // Ensure correct data key is used
                $.each(response.sub_admin_permissions, function(index, perm) {
                    let menu = perm.menu;
                    if (perm.add == 1) {
                        $(`input[name="permissions[${menu}][add]"]`).prop("checked", true);
                    }
                    if (perm.edit == 1) {
                        $(`input[name="permissions[${menu}][edit]"]`).prop("checked", true);
                    }
                    if (perm.view == 1) {
                        $(`input[name="permissions[${menu}][view]"]`).prop("checked", true);
                    }
                    if (perm.delete == 1) {
                        $(`input[name="permissions[${menu}][delete]"]`).prop("checked", true);
                    }
                });

                $("#permissionsModal").modal("show"); // Show the modal
            },
            error: function() {
                alert("Error fetching permissions.");
            }
        });
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

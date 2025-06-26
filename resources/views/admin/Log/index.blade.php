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
                                    <h4>SubAdmin Logs</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                 {{-- @php
                                $isAdmin = $isAdmin ?? false;
                               $permissions = $subadminPermissions['bookings'] ?? null;
                                // Fetch permissions for this menu
                               @endphp  --}}
                           {{--@if($isAdmin || ($permissions && $permissions->add == 1))  --}}
                                {{-- <a class="btn btn-primary mb-3" href="{{ route('booking.create') }}">Create
                                </a> --}}
{{-- @endif --}}
                                <table class="responsive table " id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>SubAdmin Name</th>
                                            <th>Section</th>
                                            <th>Action</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($logs as $log)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $log->subadmin->name ?? 'N/A' }}</td>
                                            <td>{{ $log->section }}</td>
                                            <td>{{ ucfirst($log->action) }}</td>
                                            <td>{{ $log->message }}</td>
                                            <td>{{ $log->created_at->format('d M Y') }}</td>

                                            <td>
                                                <div class="d-flex gap-4">
                                                    <div class="gap-3"
                                                        style="display: flex; align-items: center; justify-content: center; column-gap: 8px">
                                                        <form action="{{ route('logs.destroy', $log->id) }}"
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

   

@endsection

@section('js')
    
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



@endsection

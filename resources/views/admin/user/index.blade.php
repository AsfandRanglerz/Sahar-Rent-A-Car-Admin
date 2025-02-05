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
                                    <h4>Customers</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-primary mb-3" href="{{ route('user.create') }}">Create
                                </a>

                                <table class="responsive table " id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            {{-- <th>Address</th> --}}
                                            <th>Documents</th>
                                            {{-- <th>Documents2</th> --}}
                                            <th>Image</th>
                                            <th>Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>

                                                <td>
                                                    @if ($user->email)
                                                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                                    @endif
                                                </td>
                                                <td>{{ $user->phone }}</td>
                                                {{-- <td>{{ $user->address }}</td> --}}
                                                 <td>
                                                    @if ($user->emirate_id||$user->passport||$user->driving_license)
                                                        <ul>
                                                            @if ($user->emirate_id)
                                                                <li>
                                                                    <a href="{{ asset('storage/app/public/' . $user->emirate_id) }}" target="_blank">Emirate ID</a>
                                                                </li>
                                                            @endif
                                                            @if ($user->passport)
                                                                <li>
                                                                    <a href="{{ asset('storage/app/public/' . $user->passport) }}" target="_blank">Passport</a>
                                                                </li>
                                                            @endif
                                                            @if ($user->driving_license)
                                                                <li>
                                                                    <a href="{{ asset('storage/app/public/' . $user->driving_license) }}" target="_blank">Driving License</a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                        @elseif($user->documents)
                                                        <ul>
                                                            @if ($user->documents->emirate_id)
                                                                <li>
                                                                    <a href="{{ asset('storage/app/public/' . $user->documents->emirate_id) }}" target="_blank">Emirate ID</a>
                                                                </li>
                                                            @endif
                                                            @if ($user->documents->passport)
                                                                <li>
                                                                    <a href="{{ asset('storage/app/public/' . $user->documents->passport) }}" target="_blank">Passport</a>
                                                                </li>
                                                            @endif
                                                            @if ($user->documents->driving_license)
                                                                <li>
                                                                    <a href="{{ asset('storage/app/public/' . $user->documents->driving_license) }}" target="_blank">Driving License</a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    @else
                                                        <span>No documents uploaded</span>
                                                    @endif
                                                </td> 
                                                {{-- <td>
                                                    @if ($user->emirate_id)
                                                        <a href="{{ asset('storage/' . $user->emirate_id) }}" target="_blank">Emirate ID</a>
                                                    @endif
                                                    @if ($user->passport)
                                                        | <a href="{{ asset('storage/' . $user->passport) }}" target="_blank">Passport</a>
                                                    @endif
                                                    @if ($user->driving_license)
                                                        | <a href="{{ asset('storage/' . $user->driving_license) }}" target="_blank">Driving License</a>
                                                    @endif
                                                </td> --}}
                                                {{-- <td>
                                                    @if ($user->documents)
                                                        @php
                                                            $documents = [];
                                                            if ($user->documents->emirate_id) {
                                                                $documents[] = [
                                                                    'name' => 'Emirate Id',
                                                                    'url' => asset('public/storage/' . $user->documents->emirate_id),
                                                                ];
                                                            }
                                                            if ($user->documents->passport) {
                                                                $documents[] = [
                                                                    'name' => 'Passport',
                                                                    'url' => asset('public/storage/' . $user->documents->passport),
                                                                ];
                                                            }
                                                            if ($user->documents->driving_license) {
                                                                $documents[] = [
                                                                    'name' => 'Driving License',
                                                                    'url' => asset('public/storage/' . $user->documents->driving_license),
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
                                                        @else
                                                            <span>No documents uploaded</span>
                                                        @endif
                                                    @else
                                                        <span>No documents uploaded</span>
                                                    @endif
                                                </td> --}}
                                               
                                                <td>
                                                    @if($user->image)
                                                    <img src="{{ asset($user->image) }}" alt="" height="50"
                                                        width="50" class="image">
                        
                                                    @else
                                                    <span>No image</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <div class="badge {{ $user->availability == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
                                                        {{ $user->availability == 0 ? 'Available' : 'Not Available' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-4">
                                                        <div class="gap-3"
                                                            style="display: flex; align-items: center; justify-content: center; column-gap: 8px">



                                                            @if ($user->status == 1)
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
                                                            @endif


                                                            <a href="{{ route('user.edit', $user->id) }}"
                                                                class="btn btn-primary" style="margin-left: 10px">Edit</a>
                                                            <form action="{{ route('user.destroy', $user->id) }}"
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
                            <label for="reason">Please provide the reason for deactivating this customer:</label>
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

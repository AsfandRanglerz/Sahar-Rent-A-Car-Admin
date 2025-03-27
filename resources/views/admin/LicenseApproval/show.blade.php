@extends('admin.layout.app')
@section('title', 'Users')
@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('driver.index') }}">Back</a>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>License</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                {{-- @php
                                $isAdmin = $isAdmin ?? false;
                               $permissions = $subadminPermissions['license_approvals'] ?? null;
                                // Fetch permissions for this menu
                               @endphp  --}}
                           {{-- @if($isAdmin || ($permissions && $permissions->add == 1))  --}}
                                {{--<a class="btn btn-primary mb-3" href="{{ route('license.create') }}">Create</a>--}}
                                {{-- @endif --}}
                                <table class="responsive table" id="table-1">
                                    <thead>
                                        <tr>
                                            {{-- <th>Sr.</th> --}}
                                            <th>Name</th>
                                            <th>Email</th>
                                            {{-- <th>Phone</th> --}}
                                            <th>License</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($drivers as $driver) --}}
                                            <tr>
                                                {{-- <td>{{ $loop->iteration }}</td> --}}
                                                <td>{{  $drivers->name }}</td>
                                                <td>
                                                    {{-- @if ($driver->driver && $driver->driver->email)
                                                        <a href="mailto:{{ $driver->driver->email }}">{{ $driver->driver->email }}</a>
                                                    @endif --}}
                                                    @if ( $drivers->email)
                                                    <a href="mailto:{{ $drivers->email }}">{{ $drivers->email }}</a>
                                                @else
                                                    N/A
                                                @endif
                                                </td>
                                                {{-- <td>{{ $driver->phone }}</td> --}}
                                                <td>
                                                    
                                                    @if ($licenseApprovals && $licenseApprovals->image)
                                                        <img src="{{ asset('storage/app/public/' . $licenseApprovals->image) }}" 
                                                        alt="License Image" height="45" width="50" class="image"
                                                        style="cursor: pointer;" data-toggle="modal" 
                                                        data-target="#imageModal" 
                                                        data-image="{{ asset('storage/app/public/' . $licenseApprovals->image) }}">
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                {{-- <td>
                                                    <div class="badge {{ $LicenseApproval->status == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
                                                        {{ $LicenseApproval->status == 0 ? 'Accepted' : 'Rejected' }}
                                                    </div>
                                                </td> --}}
                                               
                                                
                                                
                                            </tr>
                                        {{-- @endforeach --}}
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
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
    
    <script>

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

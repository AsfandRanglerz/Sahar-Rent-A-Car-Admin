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
                                    <h4>Notification</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <a class="btn btn-success mb-3" href="{{ route('notification.create') }}">Add
                                    Notification</a>

                                <table class="responsive table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>User Type</th>
                                            <th>Customer Name</th>
                                            <th>Driver Name</th>
                                            <th>Title</th>
                                            <th>Message</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Salutations as $Salutation)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $Salutation->name }}</td>
                                                {{-- <td>{{ $Salutation->additional_services}}</td>
                                                @if($Salutation->packages == null)
                                                    <td class="text-danger">
                                                        <span>No record found</span>
                                                    </td>
                                                @else
                                                    <td>{{ $Salutation->packages }}</td>
                                                @endif
                                                <td>{{ $Salutation->price }}$</td> --}}
                                                {{-- <td>{{ $Salutation->less_equal_price }} $</td>
                                                <td>{{ $Salutation->above_equal_price }} per word</td>
                                                <td>{{ $Salutation->delivery_days }} days</td> --}}
                                               

                                                {{-- <td>
                                                    <div class="badge {{ $Salutation->status == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
                                                        {{ $Salutation->status == 0 ? 'Activated' : 'Deactivated' }}
                                                    </div>
                                                </td> --}}
                                                <td>
                                                    <div class="d-flex gap-4">
                                                        <a href="{{route('Salutation.Edit',$Salutation->id)}}"
                                                            class="btn btn-primary" style="margin-left: 10px">
                                                            <span class="fas fa-edit"></span> </a>
                                                            <form action="{{ route('Salutation.Destroy', $Salutation->id) }}" method="POST" style="display:inline-block; margin-left: 10px">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-flat show_confirm" data-toggle="tooltip">
                                                                    <span class="fas fa-trash-alt"></span> <!-- Delete icon -->
                                                                </button>
                                                            </form>
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
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
    $(document).on('click', '.show_confirm', function(event) {
        var form = $(this).closest("form");
        event.preventDefault();
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
</script>
@endsection

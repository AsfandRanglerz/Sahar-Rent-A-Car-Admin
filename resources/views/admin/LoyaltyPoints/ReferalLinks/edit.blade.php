@extends('admin.layout.app')
@section('title', 'Edit Subadmin')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('referals.index')}}">Back</a>
                <form id="edit_subadmin" action="{{ route('referals.update', $loyaltyPoint->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Referal Points</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>On Referal Link</label>
                                            <input type="number" placeholder="Points i.e 5" name="on_referal"
                                                id="on_referal" value="{{ old('on_referal', $loyaltyPoint->on_referal) }}" class="form-control">
                                            @error('on_referal')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Email</label>
                                            <input type="email" placeholder="Enter Your Email" name="email"
                                                id="email" value="{{ old('email', $loyaltyPoint->email) }}" class="form-control">
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>On Car Rental</label>
                                            <input type="number" placeholder="Points i.e 5" name="on_car"
                                                id="on_car" value="{{ old('on_car', $loyaltyPoint->on_car) }}" class="form-control">
                                            @error('on_car')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Address</label>
                                            <input type="text" placeholder="Enter Address" name="address"
                                                id="address" value="{{ old('address', $loyaltyPoint->address) }}" class="form-control">
                                            @error('address')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Discount</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="border: 2px solid #cbd2d8;">Loyalty Points</span>
                                                </div>
                                                <input type="number" placeholder="Enter Discount %" name="discount"
                                                id="discount" value="{{ old('discount',$loyaltyPoint->discount) }}" class="form-control">
                                            </div>
                                            <small class="text-muted">(Note: Enter the discount percentage based on loyalty points)</small>
                                                @error('discount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Image (Optional)</label>
                                            <input type="file" name="image" class="form-control" >
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        @if($loyaltypoint->image)
                                        <div class="ms-3">
                                            <img src="{{ asset($loyaltypoint->image) }}" 
                                                 alt="image" 
                                                 style="width: 80px; height: 70px;  border: 1px solid #ddd;">
                                        </div>
                                    @endif
                                    </div> --}}
                                </div>

                                <div class="card-footer text-center">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg" id="submit">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

@endsection

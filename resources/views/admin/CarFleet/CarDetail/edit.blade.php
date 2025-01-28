@extends('admin.layout.app')
@section('title', 'Edit Subadmin')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('car.index') }}">Back</a>
                <form id="edit_subadmin" action="{{ route('car.update', $CarDetail->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Edit Inventory</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Car Name</label>
                                            <input type="text" placeholder="Enter Car Name" name="car_name"
                                                id="car_name" value="{{ old('car_name', $CarDetail->car_name) }}" class="form-control">
                                            @error('car_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Price Per Hour</label>
                                            <div class="input-group">
                                            <input type="number" placeholder="Enter Price Per Hour" name="pricing"
                                                id="pricing" value="{{ old('pricing', $CarDetail->pricing) }}" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="border: 2px solid #cbd2d8;">AED</span>
                                                </div>
                                            </div>
                                                @error('pricing')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Price Per Day</label>
                                        <div class="input-group">
                                            <input type="number" placeholder="Enter Price Per Day" name="sanitized"
                                                id="sanitized" value="{{ old('sanitized', $CarDetail->sanitized) }}" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="border: 2px solid #cbd2d8;">AED</span>
                                                </div>
                                        </div>
                                                @error('sanitized')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Price Per Week</label>
                                            <div class="input-group">
                                            <input type="number" placeholder="Enter Price Per Week" name="car_feature"
                                                id="car_feature" value="{{ old('car_feature', $CarDetail->car_feature) }}" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="border: 2px solid #cbd2d8;">AED</span>
                                                </div>
                                            </div>
                                                @error('car_feature')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3" id="durations">
                                        <div class="form-group mb-2">
                                            <label>Durations</label>
                                            <select name="durations" id="durations" class="form-control">
                                                <option disabled selected>Select value</option>
                                                <option value="Per Hour" {{ $CarDetail->durations == 'Per Hour' ? 'selected' : '' }}>Per Hour</option>
                                                <option value="Per Day" {{ $CarDetail->durations == 'Per Day' ? 'selected' : '' }}>Per Day</option>
                                                <option value="Per Week" {{ $CarDetail->durations == 'Per Week' ? 'selected' : '' }}>Per Week</option>
                                            </select>
                                            @error('durations')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Phone Number</label>
                                            <input type="number" placeholder="Enter Phone Number" name="call_number"
                                                id="call_number" value="{{ old('call_number', $CarDetail->call_number) }}" class="form-control">
                                            @error('call_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>WhatsApp Number</label>
                                            <input type="number" placeholder="Enter WhatsApp Number" name="whatsapp_number"
                                                id="whatsapp_number" value="{{ old('whatsapp_number', $CarDetail->whatsapp_number) }}" class="form-control">
                                            @error('whatsapp_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Passengers</label>
                                            <input type="number" placeholder="Enter Passengers" name="passengers"
                                                id="passengers" value="{{ old('passengers', $CarDetail->passengers) }}" class="form-control">
                                            @error('passengers')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Luggage</label>
                                            <input type="number" placeholder="Enter Luggage Capacity" name="luggage"
                                                id="luggage" value="{{ old('luggage', $CarDetail->luggage) }}" class="form-control">
                                            @error('luggage')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Doors</label>
                                            <input type="number" placeholder="Enter Car Doors" name="doors"
                                                id="doors" value="{{ old('doors', $CarDetail->doors) }}" class="form-control">
                                            @error('doors')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Car Type</label>
                                            <input type="text" placeholder="Enter Car Type" name="car_type"
                                                id="car_type" value="{{ old('car_type', $CarDetail->car_type) }}" class="form-control">
                                            @error('car_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Car Type</label>
                                            <select name="car_type" class="form-control">
                                                <option disabled selected>Select value</option>
                                                <option value="Auto" {{ old('car_type', $CarDetail->car_type) == 'Auto' ? 'selected' : '' }}>Auto</option>
                                                <option value="Manual" {{ old('car_type', $CarDetail->car_type) == 'Manual' ? 'selected' : '' }}>Manual</option>
                                            </select>
                                            @error('car_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Car Play</label>
                                            <input type="text" placeholder="Enter Car Play" name="car_play"
                                                id="car_play" value="{{ old('car_play', $CarDetail->car_play) }}" class="form-control">
                                            @error('car_play')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Sanitation</label>
                                            <input type="text" placeholder="Enter Sanitation" name="sanitized"
                                                id="sanitized" value="{{ old('sanitized', $CarDetail->sanitized) }}" class="form-control">
                                            @error('sanitized')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Car Feature</label>
                                            <input type="text" placeholder="Enter Car Feature" name="car_feature"
                                                id="car_feature" value="{{ old('car_feature', $CarDetail->car_feature) }}" class="form-control">
                                            @error('car_feature')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Delivery</label>
                                            <input type="text" placeholder="Enter Delivery" name="delivery"
                                                id="delivery" value="{{ old('delivery', $CarDetail->delivery) }}" class="form-control">
                                            @error('delivery')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>PickUp</label>
                                            <input type="text" placeholder="Enter PickUp" name="pickup"
                                                id="pickup" value="{{ old('pickup', $CarDetail->pickup) }}" class="form-control">
                                            @error('pickup')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Travel Distance</label>
                                            <input type="text" placeholder="Enter Travel Distance" name="travel_distance"
                                                id="travel_distance" value="{{ old('travel_distance', $CarDetail->travel_distance) }}" class="form-control">
                                            @error('travel_distance')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    
                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3" id="durations">
                                        <div class="form-group mb-2">
                                            <label>Availability</label>
                                            <select name="availability" id="availability" class="form-control">
                                                <option disabled selected>Select value</option>
                                                <option value="with driver" {{ $CarDetail->availability == 'with driver' ? 'selected' : '' }}>with driver</option>
                                                <option value="without driver" {{ $CarDetail->availability == 'without driver' ? 'selected' : '' }}>without driver</option>
                                                <option value="Per Week" {{ old('availability') == 'Per Week' ? 'selected' : '' }}>Per Week</option>
                                            </select>
                                            @error('availability')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="0" {{ $CarDetail->status == 0 ? 'selected' : '' }}>
                                                    Activate</option>
                                                <option value="1" {{ $CarDetail->status == 1 ? 'selected' : '' }}>
                                                    Deactivate</option>
                                            </select>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 d-flex align-items-center">
                                        <div class="flex-grow-1">
                                        <div class="form-group mb-2">
                                            <label>Image</label>
                                            <input type="file" name="image" id="image" class="form-control">
                                            @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        </div>
                                            @if($CarDetail->image)
                                            <div class="ms-3">
                                                <img src="{{ asset($CarDetail->image) }}" 
                                                     alt="image" 
                                                     style="width: 80px; height: 70px; margin-left:20px; border: 1px solid #ddd;">
                                            </div>
                                            @endif
                                           
                                        
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label></label>
                                            <button type="button" class="btn btn-primary btn-sm mb-3" id="addFeatureBtn" style="margin-top:15px; ">Add Feature</button>
                                            <div id="featuresContainer">
                                                @php
                                                    $existingFeatures = explode("\n", $CarDetail->car_play); // Assuming comma-separated
                                                @endphp
                                    
                                                @if (!empty($existingFeatures))
                                                    @foreach ($existingFeatures as $feature)
                                                        <div class="d-flex align-items-center mb-2 feature-field">
                                                            <input type="text" name="features[]" class="form-control mr-2" placeholder="Enter Feature" value="{{ trim($feature) }}">
                                                            <button type="button" class="btn btn-danger btn-sm removeFeatureBtn">Remove</button>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            @error('features')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            // Add Feature Button Click
            $('#addFeatureBtn').click(function () {
                // Create a new feature input with a remove button
                const featureField = `
                    <div class="d-flex align-items-center mb-2 feature-field">
                        <input type="text" name="features[]" class="form-control mr-2" placeholder="Enter Feature" required>
                        <button type="button" class="btn btn-danger btn-sm removeFeatureBtn">Remove</button>
                    </div>
                `;
                // Append the new field to the container
                $('#featuresContainer').append(featureField);
            });
    
            // Remove Feature Button Click
            $(document).on('click', '.removeFeatureBtn', function () {
                // Remove the parent feature field
                $(this).closest('.feature-field').remove();
            });
        });
    </script>
@endsection

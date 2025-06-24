@extends('admin.layout.app')
@section('title', 'Dashboard')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <a class="btn btn-primary mb-3" href="{{ route('car.index')}}">Back</a>
                <form id="add_department" action="{{ route('car.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="card">
                                <h4 class="text-center my-4">Add Inventory</h4>
                                <div class="row mx-0 px-4">
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Car Name <span class="text-danger">*</span></label>
                                            <input type="name" placeholder="Enter Car Name" name="car_name"
                                                id="car_name" value="{{ old('car_name') }}" class="form-control">
                                            @error('car_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Price Per Day <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                            <input type="number" placeholder="Enter Price Per day" name="price_per_day"
                                                id="price_per_day" value="{{ old('price_per_day') }}" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="border:2px solid #cbd2d8;">AED</span>
                                                </div>
                                            </div>
                                                @error('price_per_day')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Price Per One Week <span class="text-danger">*</span></label>
                                            <div class ="input-group">
                                                <input type="number" placeholder="Enter Price Per week" name="price_per_week"
                                                id="price_per_week" value="{{ old('price_per_week') }}" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="border: 2px solid #cbd2d8;">AED</span>
                                                </div>
                                            </div>
                                                @error('price_per_week')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            
                                        </div>
                                    </div>

                                     <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Price Per Two Weeks <span class="text-danger">*</span></label>
                                            <div class ="input-group">
                                                <input type="number" placeholder="Enter Price Per Two weeks" name="price_per_two_week"
                                                id="price_per_two_week" value="{{ old('price_per_two_week') }}" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="border: 2px solid #cbd2d8;">AED</span>
                                                </div>
                                            </div>
                                                @error('price_per_two_week')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            
                                        </div>
                                    </div>

                                     <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Price Per Three Weeks <span class="text-danger">*</span></label>
                                            <div class ="input-group">
                                                <input type="number" placeholder="Enter Price Per Three weeks" name="price_per_three_week"
                                                id="price_per_three_week" value="{{ old('price_per_three_week') }}" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="border: 2px solid #cbd2d8;">AED</span>
                                                </div>
                                            </div>
                                                @error('price_per_three_week')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            
                                        </div>
                                    </div>

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Price Per Month <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" placeholder="Enter Price Per Month" name="price_per_month" 
                                                    id="price_per_month" value="{{ old('price_per_month') }}" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="border: 2px solid #cbd2d8;">AED</span>
                                                </div>
                                            </div>
                                            @error('price_per_month')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3" id="durations">
                                        <div class="form-group mb-2">
                                            <label>Durations</label>
                                            <select name="durations" id="durations" class="form-control">
                                                <option disabled selected>Select value</option>
                                                <option value="Per Hour" {{ old('durations') == 'Per Hour' ? 'selected' : '' }}>Per Hour</option>
                                                <option value="Per Day" {{ old('durations') == 'Per Day' ? 'selected' : '' }}>Per Day</option>
                                                <option value="Per Week" {{ old('durations') == 'Per Week' ? 'selected' : '' }}>Per Week</option>
                                            </select>
                                            @error('durations')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Phone Number <span class="text-danger">*</span></label>
                                            <input type="number" placeholder="Enter Phone Number" name="call_number"
                                                id="call_number" value="{{ old('call_number') }}" class="form-control">
                                            @error('call_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>WhatsApp Number</label>
                                            <input type="number" placeholder="Enter WhatsApp Number" name="whatsapp_number"
                                                id="whatsapp_number" value="{{ old('whatsapp_number') }}" class="form-control">
                                            @error('whatsapp_number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Passengers <span class="text-danger">*</span></label>
                                            <input type="number" placeholder="Enter Passengers" name="passengers"
                                                id="passengers" value="{{ old('passengers') }}" class="form-control">
                                            @error('passengers')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Luggage <span class="text-danger">*</span></label>
                                            <input type="number" placeholder="Enter Luggage Capacity" name="luggage"
                                                id="luggage" value="{{ old('luggage') }}" class="form-control">
                                            @error('luggage')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Doors <span class="text-danger">*</span></label>
                                            <input type="number" placeholder="Enter Car Doors" name="doors"
                                                id="doors" value="{{ old('doors') }}" class="form-control">
                                            @error('doors')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Car Type</label>
                                            <input type="text" placeholder="Enter Car Type" name="car_type"
                                                id="car_type" value="{{ old('car_type') }}" class="form-control">
                                            @error('car_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Car Type <span class="text-danger">*</span></label>
                                            <select name="car_type" class="form-control">
                                                <option disabled selected>Select value</option>
                                                <option value="Auto" {{ old('car_type') == 'Auto' ? 'selected' : '' }}>Auto</option>
                                                <option value="Manual" {{ old('car_type') == 'Manual' ? 'selected' : '' }}>Manual</option>
                                            </select>
                                            @error('car_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Car Play</label>
                                            <input type="text" placeholder="Enter Car Name" name="car_play"
                                                id="car_play" value="{{ old('car_play') }}" class="form-control">
                                            @error('text')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Sanitation</label>
                                            <input type="text" placeholder="Enter Car Sanitized" name="sanitized"
                                                id="sanitized" value="{{ old('sanitized') }}" class="form-control">
                                            @error('text')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Car Feature</label>
                                            <input type="text" placeholder="Enter Car Feature" name="car_feature"
                                                id="car_feature" value="{{ old('car_feature') }}" class="form-control">
                                            @error('text')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Delivery</label>
                                            <input type="text" placeholder="Enter Delivery" name="delivery"
                                                id="delivery" value="{{ old('delivery') }}" class="form-control">
                                            @error('text')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>PickUp</label>
                                            <input type="text" placeholder="Enter PickUp" name="pickup"
                                                id="pickup" value="{{ old('pickup') }}" class="form-control">
                                            @error('text')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Travel Distance</label>
                                            <input type="text" placeholder="Enter Travel Distance" name="travel_distance"
                                                id="travel_distance" value="{{ old('travel_distance') }}" class="form-control">
                                            @error('text')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    
                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3" id="durations">
                                        <div class="form-group mb-2">
                                            <label>Availability</label>
                                            <select name="availability" id="availability" class="form-control">
                                                <option disabled selected>Select value</option>
                                                <option value="with driver" {{ old('availability') == 'with driver' ? 'selected' : '' }}>with driver</option>
                                                <option value="without driver" {{ old('availability') == 'without driver' ? 'selected' : '' }}>without driver</option>
                                                <option value="Per Week" {{ old('availability') == 'Per Week' ? 'selected' : '' }}>Per Week</option>
                                            </select>
                                            @error('availability')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-sm-6 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Password</label>
                                            <input type="password" placeholder="Enter Your Password" name="password"
                                                id="password" value="{{ old('password') }}" class="form-control">
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Image <span class="text-danger">*</span></label>
                                            <input type="file" placeholder="Enter Your Image"name="image" value="{{ old('image') }}"
                                                class="form-control">
                                                <small text-muted>(Image should be of size 2MB)</small>
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Add Features</label>
                                            <textarea type="text" placeholder="Enter Features" name="car_play"
                                                id="car_play" value="{{ old('car_play') }}" class="form-control"></textarea>
                                            @error('text')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Activate</option>
                                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Deactivate</option>
                                            </select>
                                            @error('status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4 pl-sm-0 pr-sm-3">
                                        <div class="form-group mb-2">
                                            <!-- Add Features Button -->
                                            <label class="d-block mb-4"></label>
                                            <button type="button" class="btn btn-primary" id="addFeatureBtn" style="margin-top:7px;">Add Feature</button>
                                            <!-- Container for dynamically added fields -->
                                            <div id="featuresContainer" class="mt-3"></div>
                                            @error('feature')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-center">
                                    <div class="col">
                                        <button type="submit" class="btn btn-success mr-1 btn-bg"
                                            id="submit">Save</button>
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const imageInput = document.querySelector('input[name="image"]');
    
            imageInput.addEventListener("change", function () {
                const file = this.files[0];
                const maxSize = 2 * 1024 * 1024; // 4MB in bytes
    
                // Remove previous error if any
                const existingError = this.parentElement.querySelector(".text-danger");
                if (existingError) existingError.remove();
    
                if (file && file.size > maxSize) {
                    // Clear the input
                    this.value = "";
    
                    // Show custom error message
                    const errorDiv = document.createElement("div");
                    errorDiv.className = "text-danger";
                    errorDiv.textContent = "Image size must not exceed 2MB.";
                    this.parentElement.appendChild(errorDiv);
                }
            });
        });
    </script>
    
@endsection


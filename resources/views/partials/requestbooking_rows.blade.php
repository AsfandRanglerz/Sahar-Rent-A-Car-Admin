@foreach ($requestbookings as $requestbooking)
@php
    $assigned = $requestbooking->assign->whereNotNull('driver_id')->first();
    $driverCompleted = $requestbooking->assign
    ->whereNotNull('driver_id')
    ->contains(function ($assigned) {
        return $assigned->status == 1;
    });
@endphp
<tr >
<td>{{ $loop->iteration }}</td>
<td>{{ $requestbooking->car_id }}</td>
<td >
{{-- <div class="badge {{ $requestbooking->status == 0 ? 'badge-success' : 'badge-primary' }} badge-shadow">
    {{ $requestbooking->status == 0 ? 'Active' : 'Completed' }}
</div> --}}
{{-- @if($requestbooking->status == 2)
@if(is_null($requestbooking->driver_id))
<div class="badge badge-warning badge-shadow">Pending</div>
@else
<div class="badge badge-warning badge-shadow">Requested</div>
@endif 
@elseif($requestbooking->status == 0)
<div class="badge badge-success badge-shadow">Active</div>
@elseif($requestbooking->status == 1)
<div class="badge badge-primary badge-shadow">Completed</div>
@elseif($requestbooking->status == 3)
@if(is_null($requestbooking->driver_id))
    <div class="badge badge-warning badge-shadow">Pending</div>
@else
    <div class="badge badge-warning badge-shadow">Requested</div>
@endif

@endif --}}

    @if($requestbooking->status == 2)
        {{-- Always show Pending from request_booking table --}}
        <div class="badge badge-warning badge-shadow">Pending</div>

        @elseif($driverCompleted)
        {{-- Show Completed if any driver_id status is 1 --}}
        <div class="badge badge-primary badge-shadow">Completed</div>

        @elseif($requestbooking->status == 0)
        <div class="badge badge-success badge-shadow">Active</div>
        
        @elseif($requestbooking->status == 3 || $requestbooking->status == 1)
        @if($assigned)
            @if($assigned->status == 0)
                <div class="badge badge-success badge-shadow">Active</div>
            {{-- @elseif($assigned->status == 1)
                <div class="badge badge-primary badge-shadow">Completed</div> --}}
            @elseif($assigned->status == 3)
                @if(is_null($assigned->driver_id))
                    <div class="badge badge-warning badge-shadow">Pending</div>
                @else
                    <div class="badge badge-warning badge-shadow">Requested</div>
                @endif
            @else
                <div class="badge badge-secondary badge-shadow">Unknown</div>
            @endif
        @else
            {{-- Status is 3 but assigned entry not yet made --}}
            <div class="badge badge-warning badge-shadow">Pending</div>
        @endif

    @else
        <div class="badge badge-secondary badge-shadow">Unknown</div>
    @endif

</td>
<td>{{ $requestbooking->full_name }}</td>
{{-- <td>
@if($requestbooking->driver_name)    
{{ $requestbooking->driver_name }}
@else
<span>--</span>
@endif
</td> --}}
<td>
@if ($requestbooking->email)
    <a href="mailto:{{ $requestbooking->email }}">{{ $requestbooking->email }}</a>
@endif
</td>
<td>{{ $requestbooking->phone }}</td>


<td>{{ $requestbooking->self_pickup }}</td>
{{-- <td>{{ $requestbooking->durations }}</td> --}}
{{-- <td>{{ $requestbooking->call_number }}</td>
<td>{{ $requestbooking->whatsapp_number }}</td> --}}
<td>
@if($requestbooking->pickup_address)    
{{ $requestbooking->pickup_address }}
@else
<span>--</span>
@endif
</td>
<td>
@if($requestbooking->pickup_date)    
{{ $requestbooking->pickup_date }}
@else
<span>--</span>
@endif
</td>
<td>
@if($requestbooking->pickup_time)    
{{ $requestbooking->pickup_time }}
@else
<span>--</span>
@endif
</td>
{{-- <td>{{ $requestbooking->self_dropoff }}</td>
<td>
@if($requestbooking->dropoff_address)
{{ $requestbooking->dropoff_address }}
@else
<span>--</span>
@endif
</td>
<td>
@if($requestbooking->dropoff_date)
{{ $requestbooking->dropoff_date }}
@else
<span>--</span>
@endif
</td>
<td>
@if($requestbooking->dropoff_time)
{{ $requestbooking->dropoff_time }}
@else
<span>--</span>
@endif
</td> --}}
<td>
@if($requestbooking->driver_required) 
{{ $requestbooking->driver_required }}
@else
<span>--</span>
@endif
</td>
{{-- <td>{!! $requestbooking->car_play !!}</td> --}}
{{-- <td>
@if (!empty($requestbooking->car_play))
    @php
        $features = explode("\n", $requestbooking->car_play); // Assuming features are stored as a comma-separated string
    @endphp
    <ul>
        @foreach ($features as $feature)
            <li>{{ trim($feature) }}</li>
        @endforeach
    </ul>
@else
    N/A
@endif
</td> --}}

{{--<td>{{ $requestbooking->delivery }}</td>
<td>{{ $requestbooking->pickup }}</td>
<td>{{ $requestbooking->travel_distance }}</td> --}} 
{{-- <td>
<div class="badge {{ $requestbooking->status == 0 ? 'badge-success' : 'badge-danger' }} badge-shadow">
    {{ $requestbooking->status == 0 ? 'Activated' : 'Deactivated' }}
</div>
</td>
<td>
<img src="{{ asset($requestbooking->image) }}" alt="" height="50"
    width="50" class="image">
</td> --}}

<td>
<div class="d-flex gap-4">
    <div class="gap-3"
        style="display: flex; align-items: left; justify-content: center; column-gap: 8px">



        {{-- @if ($user->status == 1)
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
        @endif --}}

        @if($isAdmin || ($permissions && $permissions->edit == 1))
        {{-- <a href="{{ route('requestbooking.edit', $requestbooking->id) }}" 
            class="btn btn-primary" style="margin-left: 10px">Assign</a>  --}}
            <a href="javascript:void(0);" class="btn btn-primary assign-driver-btn" 
            data-id="{{ $requestbooking->id }}" data-toggle="modal" 
            data-target="#assignDriverModal">
                Assign Driver
            </a>
            @php
            // Check if assigned driver status is 0
            $assignedDriver = $requestbooking->assign->whereNotNull('driver_id')->first();
            // $isDisabled = !($assignedDriver && $assignedDriver->status == 0);
        @endphp
    
        @if($assignedDriver && $assignedDriver->status == 0)
            <form action="{{ route('requestbooking.markCompleted', $requestbooking->id) }}" 
                  method="POST" style="display:inline-block; margin-left: 10px;">
                @csrf
                <button type="submit" class="btn btn-success"> {{-- {{ $isDisabled ? 'disabled' : '' }} --}}
                    Mark as Completed
                </button>
            </form>
        @endif
        
       @endif
        @if($isAdmin || ($permissions && $permissions->delete == 1))
            <form action="{{ route('requestbooking.destroy', $requestbooking->id) }}"
            method="POST"
            style="display:inline-block; margin-left: 1px">
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

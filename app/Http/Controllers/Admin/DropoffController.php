<?php

namespace App\Http\Controllers\Admin;

use Log;
use Carbon\Carbon;
use App\Models\Dropoff;
use Illuminate\Http\Request;
use App\Models\RequestBooking;
use App\Http\Controllers\Controller;

class DropoffController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $tomorrow = Carbon::now()->subDay();
        Log::info('Tomorrow date: ' . $tomorrow->toDateString()); 
return $tomorrow->toDateString();
        $crone = RequestBooking::where('assigned_dropoff', 0) // Only update unassigned drop-offs
            ->whereDate('dropoff_date', $tomorrow->toDateString())->get();
            return $crone;
        $dropoffs = RequestBooking::where('assigned_dropoff', 1)->with('driver')->get();
        return view('admin.RequestBooking.dropoff.index', compact('dropoffs'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Log;
use Carbon\Carbon;
use App\Models\Driver;
use App\Models\Dropoff;
use Illuminate\Http\Request;
use App\Models\RequestBooking;
use App\Http\Controllers\Controller;

class DropoffController extends Controller
{
    public function index()
    {
        

        $dropoffs = RequestBooking::whereIn('status', [0, 2, 3])
        ->whereNotNull('dropoff_address')
        ->with('driver')
        ->get();
        $drivers = Driver::all();
        return view('admin.RequestBooking.dropoff.index', compact('dropoffs','drivers'));
    }

    public function dropoffCounter()
{
    $dropoffCount = RequestBooking::
        where('status', 2)
        ->whereNotNull('dropoff_address')
        ->count();

    return response()->json(['count' => $dropoffCount]);
}


    public function destroy($id)
    {
        $dropoff = RequestBooking::find($id);
        $dropoff->delete();
        return redirect()->route('dropoffs.index')->with(['message' => 'Dropoff Deleted Successfully']);
    }
}

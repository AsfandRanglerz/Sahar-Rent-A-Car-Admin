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
    public function index(Request $request)
    {
        

        // $dropoffs = RequestBooking::whereIn('status', [0, 2, 3, 1])
        // ->whereNotNull('dropoff_address')
        // // ->with('driver')
        // ->with('assign')
        // ->latest()
        // ->get();
        // $drivers = Driver::all();
        $status = $request->get('status');

        // $query = RequestBooking::with('assign')->whereNotNull('dropoff_address');

        $query = RequestBooking::with('assign')
    ->whereNotNull('dropoff_address')
    ->where('status', '!=', 1) // <-- Exclude bookings with status = 1
    ->whereDoesntHave('assign', function ($q) {
        $q->where('status', 1)
          ->whereNotNull('driver_id')
          ->whereNotNull('dropoff_driver_id');
    });

    
        if ($status) {
            $query = $query->where(function ($q) use ($status) {
                if ($status === 'Completed') {
                    $q->whereHas('assign', function ($subQ) {
                        $subQ->where('status', 1);
                    });
                } elseif ($status === 'Active') {
                    $q->where(function ($subQ) {
                        $subQ->where('status', 0)
                             ->orWhereHas('assign', function ($a) {
                                 $a->where('status', 0);
                             });
                    });
                } elseif ($status === 'Pending') {
                    $q->where('status', 2)
                      ->orWhere(function ($subQ) {
                          $subQ->where('status', 3)->whereDoesntHave('assign')
                              ->orWhereHas('assign', function ($a) {
                                  $a->where('status', 3)->whereNull('dropoff_driver_id');
                              });
                      });
                } elseif ($status === 'Requested') {
                    $q->whereHas('assign', function ($a) {
                        $a->where('status', 3)->whereNotNull('dropoff_driver_id');
                    });
                }
            });
        }
    
        $dropoffs = $query->whereIn('status', [0, 2, 3, 1])->latest()->get();
        $drivers = Driver::all();
        return view('admin.RequestBooking.dropoff.index', compact('dropoffs','drivers'));
    }

    public function dropoffCounter()
{
    $dropoffCount = RequestBooking::where('dropoff_driver_id', null)
        ->whereNotNull('dropoff_address')
        ->count();

    return response()->json(['count' => $dropoffCount]);
}

public function markDropoffCompleted($id)
{
    $requestBooking = RequestBooking::with('assign')->findOrFail($id);

    foreach ($requestBooking->assign as $assigned) {
        if ($assigned->dropoff_driver_id) {
            $assigned->status = 1;
            $assigned->save();

            // Make dropoff driver available again
            Driver::where('id', $assigned->dropoff_driver_id)->update(['is_available' => 1]);
        }
    }

    // Check if all assigned entries have both driver and dropoff marked completed
    $allCompleted = true;
    foreach ($requestBooking->assign as $assigned) {
        if (($assigned->driver_id && $assigned->status != 1) || 
            ($assigned->dropoff_driver_id && $assigned->status != 1)) {
            $allCompleted = false;
            break;
        }
    }

    if ($allCompleted) {
        $requestBooking->status = 1;
        $requestBooking->save();
    }

    return redirect()->back()->with('success', 'Dropoff marked as completed successfully!');
}


    public function destroy($id)
    {
        $dropoff = RequestBooking::find($id);
        $dropoff->delete();
        return redirect()->route('dropoffs.index')->with(['message' => 'Dropoff Deleted Successfully']);
    }
}

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
        

        $dropoffs = RequestBooking::where('assigned_dropoff', 1)
        ->where('status', 0)
        ->whereNotNull('driver_id')
        ->with('driver')
        ->get();
        return view('admin.RequestBooking.dropoff.index', compact('dropoffs'));
    }

    public function destroy($id)
    {
        $dropoff = RequestBooking::find($id);
        $dropoff->delete();
        return redirect()->route('dropoffs.index')->with(['message' => 'Dropoff Deleted Successfully']);
    }
}

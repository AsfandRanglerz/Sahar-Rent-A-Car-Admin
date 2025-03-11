<?php

namespace App\Http\Controllers\API;

use App\Models\CarDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

    // Optional status filtering
    $status = $request->query('status');

    // Fetch cars belonging to the authenticated user
    $cars = CarDetails::where('user_id', $userId) // Assuming 'user_id' column exists in 'car_details'
        ->when($status !== null, function ($query) use ($status) {
            return $query->where('status', $status);
        })
        ->get();

    return response()->json([
        'cars' => $cars
    ]);
    }
}

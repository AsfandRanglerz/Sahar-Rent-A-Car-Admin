<?php

namespace App\Http\Controllers\API;

use App\Models\CarDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

    // Optional status filtering
    $status = $request->query('status');

    $favoriteCarIds = DB::table('favorites')
    ->where('user_id', $userId)
    ->pluck('car_id')
    ->toArray();

    $bookedCarIds = DB::table('bookings')
        ->whereIn('status', [3,0]) // Assuming 2 = ongoing, 3 = upcoming
        ->pluck('car_id')
        ->merge(
            DB::table('request_bookings')
                ->whereIn('status', [3,0]) // 1 = completed
                ->pluck('car_id')
        )
        ->unique()
        ->toArray();
        // Fetch cars belonging to the authenticated user
        $cars = CarDetails::where('status', 0)
        ->whereNotIn('car_id', $bookedCarIds)
        ->orderBy('id', 'desc')
     // 'user_id' column exists in 'car_details'
        ->select(['id','car_id', 'car_name','call_number', 'price_per_day','price_per_week','price_per_month', 'passengers', 'luggage', 'doors', 'car_type','feature','image'])
        ->get()
        ->map(function ($car) use ($favoriteCarIds) {
            // Remove unwanted characters but keep spaces
            // $cleanedFeature = preg_replace('/[\r\n\t]+/', ' ', $car->feature);
            // $cleanedFeature = trim(preg_replace('/\s+/', ' ', $cleanedFeature)); // Clean extra spaces
            $car->feature = preg_split('/\r\n|\r|\n/', $car->feature);
            // Clean extra spaces from each item
            $car->feature = array_filter(array_map('trim', $car->feature));
            // Convert to array based on space separation
            // $car->feature = explode(' ', $cleanedFeature);

            $car->is_liked = in_array($car->car_id, $favoriteCarIds, false) ? true : false;

            $car->like_message = $car->is_liked ? "You liked this car!" : "";
            return $car;
        });

        return response()->json([
        'cars' => $cars
    ]);
    }

    public function getCarPriceDetails(Request $request)
    {
        $userId = Auth::id(); // Get logged-in user ID
        
        // if (!$userId) {
        //     return response()->json(['message' => 'Unauthorized'], 401);
        // }
        $carId = $request->input('id');
        $carDetails = CarDetails::
        select(['car_id', 'price_per_day', 'price_per_week', 'price_per_month'])
        ->where('id', $carId)
        ->first();

        // if (!$carDetails) {
        //     return response()->json(['message' => 'Car details not found'], 404);
        // }

        return response()->json([
            'price_details' => $carDetails,
            // 'user_id' => $userId, // Include the authenticated user ID in the response
            // 'car_id' => $carDetails->car_id,
            // 'hourly_price' => $carDetails->pricing,  // Assuming JSON format
            // 'daily_price' => $carDetails->sanitized,
            // 'weekly_price' => $carDetails->car_feature, // Assuming JSON format
        ]);
    }

    public function relatedSearch(Request $request)
{
    $userId = Auth::id();

    // Step 1: Get user favorite car_ids
    $favoriteCarIds = DB::table('favorites')
        ->where('user_id', $userId)
        ->pluck('car_id')
        ->toArray();

    // Step 2: Get completed bookings (status = 1) with timestamps
    // $bookings = DB::table('bookings')
    //     ->where('status', 1)
    //     ->select('car_id', 'updated_at')
    //     ->get();

    // $requestBookings = DB::table('request_bookings')
    //     ->where('status', 1)
    //     ->select('car_id', 'updated_at')
    //     ->get();

    // // Step 3: Merge and sort by most recent
    // $recentCompleted = $bookings->merge($requestBookings)
    //     ->sortByDesc('updated_at')
    //     ->pluck('car_id')
    //     ->unique()
    //     ->take(5)
    //     ->toArray();

      // Exclude cars that are booked, pending, or requested
      $excludedCarIds = DB::table('bookings')
      ->whereIn('status', [0, 2, 3])
      ->pluck('car_id')
      ->merge(
          DB::table('request_bookings')
              ->whereIn('status', [0, 2, 3])
              ->pluck('car_id')
      )
      ->unique()
      ->toArray();

        $allBookings = DB::table('bookings')
        ->where('status', 1)
        ->pluck('car_id')
        ->toArray();

        $allRequestBookings = DB::table('request_bookings')
        ->where('status', 1)
        ->pluck('car_id')
        ->toArray();

    //  $mergedCarIds = array_merge($allBookings, $allRequestBookings);
    // $popularCarCounts = array_count_values($mergedCarIds);
    // arsort($popularCarCounts); // Sort descending

    // $popularCarIds = array_keys(array_slice($popularCarCounts, 0, 5, true));

    // // Step 4: Merge related + popular (no duplicates)
    // $combinedCarIds = array_unique(array_merge($recentCompleted, $popularCarIds));
    $mergedCarIds = array_merge($allBookings, $allRequestBookings);
    $popularCarCounts = array_count_values($mergedCarIds);
    arsort($popularCarCounts); // sort descending by booking count

    $sortedPopularCarIds = array_keys($popularCarCounts);
    // Step 4: Fetch car details
    $relatedCars = CarDetails::where('status', 0)
    ->whereIn('car_id', $sortedPopularCarIds)
    ->whereNotIn('car_id', $excludedCarIds) // Exclude booked cars
    ->orderByRaw("FIELD(car_id, '" . implode("','", $sortedPopularCarIds) . "')") 
        ->select([
            'id','car_id','car_name','call_number','price_per_day','price_per_week','price_per_month',
            'passengers','luggage','doors','car_type','feature','image'
        ])
        ->take(5)
        ->get()
        ->map(function ($car) use ($favoriteCarIds) {
            // Clean and explode feature string
            // $cleanedFeature = preg_replace('/[\r\n\t]+/', ' ', $car->feature);
            // $cleanedFeature = trim(preg_replace('/\s+/', ' ', $cleanedFeature));
            // $car->feature = explode(' ', $cleanedFeature);
            $car->feature = preg_split('/\r\n|\r|\n/', $car->feature);
            // Clean extra spaces from each item
            $car->feature = array_filter(array_map('trim', $car->feature));
            // Liked status
            $car->is_liked = in_array($car->car_id, $favoriteCarIds);
            $car->like_message = $car->is_liked ? "You liked this car!" : "";

            return $car;
        });

    // Step 5: Return JSON response
    return response()->json([
        'related_cars' => $relatedCars
    ]);
}

public function show(Request $request, $car_id)
{
    // $request->validate([
    //     'car_id' => 'required|integer|exists:car_details,car_id',
    // ]);

    $userId = Auth::id(); // Get the logged-in user's ID

    $favoriteCarIds = DB::table('favorites')
    ->where('user_id', $userId)
    ->pluck('car_id')
    ->toArray();
    
    // Fetch the car details — optionally, you can filter using $userId if needed
    $car = CarDetails::where('car_id', $car_id)
        ->select([
            'id',
            'car_id',
            'car_name',
            'call_number',
            'price_per_day',
            'price_per_week',
            'price_per_month',
            'passengers',
            'luggage',
            'doors',
            'car_type',
            'feature',
            'image',
        ])
        ->first();

        if ($car) {
            // Clean and convert feature string to array
            // $cleanedFeature = preg_replace('/[\r\n\t]+/', ' ', $car->feature);
            // $cleanedFeature = trim(preg_replace('/\s+/', ' ', $cleanedFeature));
            // $car->feature = explode(' ', $cleanedFeature);
            $car->feature = preg_split('/\r\n|\r|\n/', $car->feature);
            // Clean extra spaces from each item
            $car->feature = array_filter(array_map('trim', $car->feature));
            // Add favorite status
            $car->is_liked = in_array($car->car_id, $favoriteCarIds);
            $car->like_message = $car->is_liked ? "You liked this car!" : "";
        }

        return response()->json([
            // 'status' => true,
            // 'user_id' => $userId,
            'data' => $car,
        ]);
    
}
public function filterCars(Request $request)
{
    $userId = Auth::id(); // Get logged-in user ID
    // $user = User::find($userId); // Fetch user details

    // if (!$user) {
    //     return response()->json(['success' => false, 'message' => 'User not found'], 404);
    // }

    $query = CarDetails::select([
        'id', 'car_id', 'car_name','call_number','price_per_day','price_per_week','price_per_month', 'passengers', 'luggage', 
        'doors', 'car_type','feature', 'image'
    ]);

    // Get input from FormData
    $location = $request->input('location');
    // $vehicleType = $request->input('vehicle_type');
    $vehicleType = $request->input('car_type');
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');

    // Apply filters if provided
    if (!empty($location)) {
        $query->where('location', 'LIKE', '%' . $location . '%');
    }

    // if (!empty($vehicleType)) {
    //     $query->where('vehicle_type', $vehicleType);
    // }
    if (!empty($vehicleType)) {
        $query->where('car_type', $vehicleType);
    }

    if (!empty($minPrice) && !empty($maxPrice)) {
        $query->whereBetween('price_per_day', [(int)$minPrice, (int)$maxPrice]);
    }

    // Fetch filtered cars
    $cars = $query->get();

    foreach ($cars as $car) {
        $car->feature = preg_split('/\r\n|\r|\n/', $car->feature);
        $car->feature = array_filter(array_map('trim', $car->feature));
    }
    
    return response()->json([
        'success' => true,
        // 'user_id' => $userId,
        'data' => $cars
    ]);
}
}

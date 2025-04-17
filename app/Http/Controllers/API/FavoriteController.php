<?php

namespace App\Http\Controllers\API;

use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggleFavorite(Request $request)
    {
        $user = Auth::user();
        $car_id = $request->car_id;

        $favorite = Favorite::where('user_id', $user->id)->where('car_id', $car_id)->first();

        if ($favorite) {
            $favorite->delete(); // Unlike
            return response()->json(['message' => 'Car removed from favorites', 'liked' => false]);
        } else {
            Favorite::create(['user_id' => $user->id, 'car_id' => $car_id]); // Like
            return response()->json(['message' => 'Car added to favorites', 'liked' => true]);
        }
    }

    // public function getFavorites()
    // {
    //     $user = Auth::user();
    //     $favorites = Favorite::where('user_id', $user->id)->with(['car:car_id,car_name,price_per_day,price_per_week,price_per_month,passengers,luggage,doors,car_type,feature,image'])
    //     ->get();
        
    //     return response()->json($favorites);
    // }

    public function getFavorites() 
{
    $user = Auth::user();

    $favorites = Favorite::where('user_id', $user->id)
        ->with(['car:id,car_id,car_name,price_per_day,price_per_week,price_per_month,passengers,luggage,doors,car_type,feature,image'])
        ->get()
        ->map(function ($favorite) {
            $car = $favorite->car;
            if ($car) {
                // Clean the feature string
                $car->feature = preg_split('/\r\n|\r|\n/', $car->feature);
                // Clean extra spaces from each item
                $car->feature = array_filter(array_map('trim', $car->feature));
                // Always true, since these are favorites
                $car->is_liked = true;
                $car->like_message = "You liked this car!";
                return $car;
            }
        });

    return response()->json([
        'favorites' => $favorites

    ]);
}

}

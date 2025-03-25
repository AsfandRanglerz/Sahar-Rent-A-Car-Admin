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

    public function getFavorites()
    {
        $user = Auth::user();
        $favorites = Favorite::where('user_id', $user->id)->with(['car:car_id,car_name,pricing,passengers,luggage,doors,car_type,car_play,sanitized,car_feature,image'])
        ->get();
        
        return response()->json($favorites);
    }
}

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ContactUsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//#################  UserDocument ########################
Route::post('/userdocument',[AuthController::class,'uploadDocument'])->middleware('auth:sanctum');
Route::post('/driverdocument',[AuthController::class,'driverdocument'])->middleware('auth:sanctum');
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
});

//#################  Bookings  ###########################
Route::post('/bookings',[BookingController::class,'createBooking']);

//#################  Payment  ############################
Route::post('/deposit', [PaymentController::class, 'processDeposit']);

//#################  ContactUs  ##########################
Route::post('/contactus',[ContactUsController::class,'contact']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

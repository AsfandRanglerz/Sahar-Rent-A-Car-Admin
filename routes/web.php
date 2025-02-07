<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TeamAController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\SubadminController;
use App\Http\Controllers\Admin\CarDetailsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\LoyaltyPointsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*Admin routes
 * */
Route::get('/admin',[AuthController::class,'getLoginPage']);
Route::post('/login',[AuthController::class,'Login']);
Route::get('/admin-forgot-password',[AdminController::class,'forgetPassword']);
Route::post('/admin-reset-password-link',[AdminController::class,'adminResetPasswordLink']);
Route::get('/change_password/{id}',[AdminController::class,'change_password']);
Route::post('/admin-reset-password',[AdminController::class,'ResetPassword']);

Route::prefix('admin')->middleware('admin')->group(function (){
    Route::get('dashboard',[AdminController::class,'getdashboard']);
    Route::get('profile',[AdminController::class,'getProfile']);
    Route::post('update-profile',[AdminController::class,'update_profile']);
    Route::get('Privacy-policy',[SecurityController::class,'PrivacyPolicy']);
    Route::get('privacy-policy-edit',[SecurityController::class,'PrivacyPolicyEdit']);
    Route::post('privacy-policy-update',[SecurityController::class,'PrivacyPolicyUpdate']);
    Route::get('term-condition',[SecurityController::class,'TermCondition']);
    Route::get('term-condition-edit',[SecurityController::class,'TermConditionEdit']);
    Route::post('term-condition-update',[SecurityController::class,'TermConditionUpdate']);
    Route::get('logout',[AdminController::class,'logout']);


 // ############ User #################
 Route::controller(UserController::class)->group(function () {
    Route::get('/user',  'index')->name('user.index');
    Route::get('/user-create',  'create')->name('user.create');
    Route::post('/user-store',  'store')->name('user.store');
    Route::get('/user-edit/{id}',  'edit')->name('user.edit');
    Route::post('/user-update/{id}',  'update')->name('user.update');
    Route::delete('/user-destroy/{id}',  'destroy')->name('user.destroy');
});

Route::post('/activate/{id}', [UserController::class, 'active'])->name('user.activate');
Route::post('/deactivate/{id}', [UserController::class, 'deactive'])->name('user.deactivate');


 // ############ Driver #################
 Route::controller(DriverController::class)->group(function () {
    Route::get('/driver',  'index')->name('driver.index');
    Route::get('/driver-create',  'create')->name('driver.create');
    Route::post('/driver-store',  'store')->name('driver.store');
    Route::get('/driver-edit/{id}',  'edit')->name('driver.edit');
    Route::post('/driver-update/{id}',  'update')->name('driver.update');
    Route::delete('/driver-destroy/{id}',  'destroy')->name('driver.destroy');
});

Route::post('/driverActivate/{id}', [DriverController::class, 'active'])->name('driver.activate');
Route::post('/driverDeactivate/{id}', [DriverController::class, 'deactive'])->name('driver.deactivate');

 // ############ subadmin #################
 Route::controller(SubadminController::class)->group(function () {
    Route::get('/subadmin',  'index')->name('subadmin.index');
    Route::get('/subadmin-create',  'create')->name('subadmin.create');
    Route::post('/subadmin-store',  'store')->name('subadmin.store');
    Route::get('/subadmin-edit/{id}',  'edit')->name('subadmin.edit');
    Route::post('/subadmin-update/{id}',  'update')->name('subadmin.update');
    Route::delete('/subadmin-destroy/{id}',  'destroy')->name('subadmin.destroy');
//     Route::get('/subadmin/getPermissions',  'getPermissions')->name('subadmin.getPermissions');
// Route::post('/subadmin/savePermissions',  'savePermissions')->name('subadmin.savePermissions');

});

 // ############ Car Details #################
 Route::controller(CarDetailsController::class)->group(function () {
    Route::get('/car',  'index')->name('car.index');
    Route::get('/car-create',  'create')->name('car.create');
    Route::post('/car-store',  'store')->name('car.store');
    Route::get('/car-edit/{id}',  'edit')->name('car.edit');
    Route::post('/car-update/{id}',  'update')->name('car.update');
    Route::delete('/car-destroy/{id}',  'destroy')->name('car.destroy');
});

// ############ Notification #################
Route::controller(NotificationController::class)->group(function () {
    Route::get('/notification',  'index')->name('notification.index');
    Route::get('/notification-create',  'create')->name('notification.create');
    Route::post('/notification-store',  'store')->name('notification.store');
    Route::get('/notification-edit/{id}',  'edit')->name('notification.edit');
    Route::post('/notification-update/{id}',  'update')->name('notification.update');
    Route::delete('/notification-destroy/{id}',  'destroy')->name('notification.destroy');
});

Route::controller(LicenseController::class)->group(function () {
    Route::get('/license',  'index')->name('license.index');
    Route::get('/license-create',  'create')->name('license.create');
    Route::post('/license-store',  'store')->name('license.store');
    Route::get('/license-edit/{id}',  'edit')->name('license.edit');
    Route::post('/license-update/{id}',  'update')->name('license.update');
    Route::delete('/license-destroy/{id}',  'destroy')->name('license.destroy');
});
Route::post('/LicenseApprovalActivate/{id}', [LicenseController::class, 'active'])->name('license.activate');
Route::post('/LicenseApprovalDeactivate/{id}', [LicenseController::class, 'deactive'])->name('license.deactivate');

Route::controller(LoyaltyPointsController::class)->group(function () {
    Route::get('/loyaltypoints',  'index')->name('loyaltypoints.index');
    Route::get('/loyaltypoints-create',  'create')->name('loyaltypoints.create');
    Route::post('/loyaltypoints-store',  'store')->name('loyaltypoints.store');
    Route::get('/loyaltypoints-edit/{id}',  'edit')->name('loyaltypoints.edit');
    Route::post('/loyaltypoints-update/{id}',  'update')->name('loyaltypoints.update');
    Route::delete('/loyaltypoints-destroy/{id}',  'destroy')->name('loyaltypoints.destroy');
});

Route::post('/subadminActivate/{id}', [SubadminController::class, 'active'])->name('subadmin.activate');
Route::post('/subadminDeactivate/{id}', [SubadminController::class, 'deactive'])->name('subadmin.deactivate');

Route::controller(BookingController::class)->group(function () {
    Route::get('/booking',  'index')->name('booking.index');
    Route::get('/booking-create',  'create')->name('booking.create');
    Route::post('/booking-store',  'store')->name('booking.store');
    Route::get('/booking-edit/{id}',  'edit')->name('booking.edit');
    Route::post('/booking-update/{id}',  'update')->name('booking.update');
    Route::delete('/booking-destroy/{id}',  'destroy')->name('booking.destroy');
});

});








/*Team B routes
 * */








/*Team Candidate
 * */

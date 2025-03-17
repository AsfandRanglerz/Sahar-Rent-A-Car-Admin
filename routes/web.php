<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TeamAController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DropoffController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\SubadminController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CarDetailsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\LoyaltyPointsController;
use App\Http\Controllers\Admin\RequestBookingController;
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
Route::post('/login',[AuthController::class,'Login'])->name('login');
Route::get('/admin-forgot-password',[AdminController::class,'forgetPassword']);
Route::post('/admin-reset-password-link',[AdminController::class,'adminResetPasswordLink']);
Route::get('/change_password/{id}',[AdminController::class,'change_password']);
Route::post('/admin-reset-password',[AdminController::class,'ResetPassword']);

Route::prefix('admin')->middleware(['admin','adminOrSubadmin:dashboard','adminOrSubadmin:privacy_policy','adminOrSubadmin:terms_conditions'])->group(function (){
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
 Route::controller(UserController::class)->middleware(['admin','adminOrSubadmin:customers,view'])->group(function () {
    Route::get('/user',  'index')->name('user.index');
    // Route::get('/user-create',  'create')->name('user.create');
    // Route::post('/user-store',  'store')->name('user.store');
    // Route::get('/user-edit/{id}',  'edit')->name('user.edit');
    // Route::post('/user-update/{id}',  'update')->name('user.update');
    Route::delete('/user-destroy/{id}',  'destroy')->name('user.destroy');
    });
    Route::controller(UserController::class)->middleware(['admin','adminOrSubadmin:customers,add'])->group(function () {
        // Route::get('/user',  'index')->name('user.index');
        Route::get('/user-create',  'create')->name('user.create');
        Route::post('/user-store',  'store')->name('user.store');
        // Route::get('/user-edit/{id}',  'edit')->name('user.edit');
        // Route::post('/user-update/{id}',  'update')->name('user.update');
        // Route::delete('/user-destroy/{id}',  'destroy')->name('user.destroy');
        });
        Route::controller(UserController::class)->middleware(['admin','adminOrSubadmin:customers,edit'])->group(function () {
            // Route::get('/user',  'index')->name('user.index');
            // Route::get('/user-create',  'create')->name('user.create');
            // Route::post('/user-store',  'store')->name('user.store');
            Route::get('/user-edit/{id}',  'edit')->name('user.edit');
            Route::post('/user-update/{id}',  'update')->name('user.update');
            // Route::delete('/user-destroy/{id}',  'destroy')->name('user.destroy');
            });

    Route::post('/activate/{id}', [UserController::class, 'active'])->name('user.activate');
    Route::post('/deactivate/{id}', [UserController::class, 'deactive'])->name('user.deactivate');


    // ############ Driver #################
    Route::controller(DriverController::class)->middleware(['admin','adminOrSubadmin:drivers,view'])->group(function () {
        Route::get('/driver',  'index')->name('driver.index');
        // Route::get('/driver-create',  'create')->name('driver.create');
        // Route::post('/driver-store',  'store')->name('driver.store');
        // Route::get('/driver-edit/{id}',  'edit')->name('driver.edit');
        // Route::post('/driver-update/{id}',  'update')->name('driver.update');
        Route::delete('/driver-destroy/{id}',  'destroy')->name('driver.destroy');
    });
    Route::controller(DriverController::class)->middleware(['admin','adminOrSubadmin:drivers,add'])->group(function () {
        // Route::get('/driver',  'index')->name('driver.index');
        Route::get('/driver-create',  'create')->name('driver.create');
        Route::post('/driver-store',  'store')->name('driver.store');
        // Route::get('/driver-edit/{id}',  'edit')->name('driver.edit');
        // Route::post('/driver-update/{id}',  'update')->name('driver.update');
        // Route::delete('/driver-destroy/{id}',  'destroy')->name('driver.destroy');
    });
    Route::controller(DriverController::class)->middleware(['admin','adminOrSubadmin:drivers,edit'])->group(function () {
        // Route::get('/driver',  'index')->name('driver.index');
        // Route::get('/driver-create',  'create')->name('driver.create');
        // Route::post('/driver-store',  'store')->name('driver.store');
        Route::get('/driver-edit/{id}',  'edit')->name('driver.edit');
        Route::post('/driver-update/{id}',  'update')->name('driver.update');
        // Route::delete('/driver-destroy/{id}',  'destroy')->name('driver.destroy');
    });
    Route::post('/driverActivate/{id}', [DriverController::class, 'active'])->name('driver.activate');
    Route::post('/driverDeactivate/{id}', [DriverController::class, 'deactive'])->name('driver.deactivate');

    // ############ subadmin #################
    Route::controller(SubadminController::class)->middleware(['admin','adminOrSubadmin:sub_admins'])->group(function () {
        Route::get('/subadmin',  'index')->name('subadmin.index');
        Route::get('/subadmin-create',  'create')->name('subadmin.create');
        Route::post('/subadmin-store',  'store')->name('subadmin.store');
        Route::get('/subadmin-edit/{id}',  'edit')->name('subadmin.edit');
        Route::post('/subadmin-update/{id}',  'update')->name('subadmin.update');
        Route::delete('/subadmin-destroy/{id}',  'destroy')->name('subadmin.destroy');
        Route::get('/subadmin/getPermissions',  'getPermissions')->name('subadmin.getPermissions');
        

        // Route::get('/subadmin/get-subadmin-permissions/{subadminId}',  'getSubadminPermissions')->name('subadmin.SubadminPermissions');
        
    Route::post('/subadmin/savePermissions',  'savePermissions')->name('subadmin.savePermissions');

    });
    Route::controller(SubadminController::class)->middleware(['admin','adminOrSubadmin:sub_admins,view'])->group(function () {
        Route::get('/logs',  'logindex')->name('admin.logs');
        Route::delete('/logs-destroy/{id}',  'logdestroy')->name('logs.destroy');
    });
    // ############ Car Details #################
    Route::controller(CarDetailsController::class)->middleware(['admin','adminOrSubadmin:cars_inventory,view'])->group(function () {
        Route::get('/car',  'index')->name('car.index');
        // Route::get('/car-create',  'create')->name('car.create');
        // Route::post('/car-store',  'store')->name('car.store');
        // Route::get('/car-edit/{id}',  'edit')->name('car.edit');
        // Route::post('/car-update/{id}',  'update')->name('car.update');
        Route::delete('/car-destroy/{id}',  'destroy')->name('car.destroy');
    });
    Route::controller(CarDetailsController::class)->middleware(['admin','adminOrSubadmin:cars_inventory,add'])->group(function () {
        // Route::get('/car',  'index')->name('car.index');
        Route::get('/car-create',  'create')->name('car.create');
        Route::post('/car-store',  'store')->name('car.store');
        // Route::get('/car-edit/{id}',  'edit')->name('car.edit');
        // Route::post('/car-update/{id}',  'update')->name('car.update');
        // Route::delete('/car-destroy/{id}',  'destroy')->name('car.destroy');
    });
    Route::controller(CarDetailsController::class)->middleware(['admin','adminOrSubadmin:cars_inventory,edit'])->group(function () {
        // Route::get('/car',  'index')->name('car.index');
        // Route::get('/car-create',  'create')->name('car.create');
        // Route::post('/car-store',  'store')->name('car.store');
        Route::get('/car-edit/{id}',  'edit')->name('car.edit');
        Route::post('/car-update/{id}',  'update')->name('car.update');
        // Route::delete('/car-destroy/{id}',  'destroy')->name('car.destroy');
    });
    // ############ Notification #################
    Route::controller(NotificationController::class)->middleware(['admin','adminOrSubadmin:notifications'])->group(function () {
        Route::get('/notification',  'index')->name('notification.index');
        Route::get('/notification-create',  'create')->name('notification.create');
        Route::post('/notification-store',  'store')->name('notification.store');
        Route::get('/notification-edit/{id}',  'edit')->name('notification.edit');
        Route::post('/notification-update/{id}',  'update')->name('notification.update');
        Route::delete('/notification-destroy/{id}',  'destroy')->name('notification.destroy');
    });

    Route::controller(LicenseController::class)->middleware(['admin','adminOrSubadmin:license_approvals'])->group(function () {
        Route::get('/license',  'index')->name('license.index');
        Route::get('/license-create',  'create')->name('license.create');
        Route::post('/license-store',  'store')->name('license.store');
        Route::get('/license-edit/{id}',  'edit')->name('license.edit');
        Route::post('/license-update/{id}',  'update')->name('license.update');
        Route::delete('/license-destroy/{id}',  'destroy')->name('license.destroy');
    });
    Route::post('/LicenseApprovalActivate/{id}', [LicenseController::class, 'active'])->name('license.activate');
    Route::post('/LicenseApprovalDeactivate/{id}', [LicenseController::class, 'deactive'])->name('license.deactivate');

    // Route::controller(LoyaltyPointsController::class)->middleware(['admin','adminOrSubadmin:loyalty_points'])->group(function () {
    //     Route::get('/loyaltypoints',  'index')->name('loyaltypoints.index');
    //     Route::get('/loyaltypoints-create',  'create')->name('loyaltypoints.create');
    //     Route::post('/loyaltypoints-store',  'store')->name('loyaltypoints.store');
    //     Route::get('/loyaltypoints-edit/{id}',  'edit')->name('loyaltypoints.edit');
    //     Route::post('/loyaltypoints-update/{id}',  'update')->name('loyaltypoints.update');
    //     Route::delete('/loyaltypoints-destroy/{id}',  'destroy')->name('loyaltypoints.destroy');
    // });
    Route::controller(LoyaltyPointsController::class)->middleware(['admin','adminOrSubadmin:loyalty_points,view'])->group(function () {
        Route::get('/loyaltypoints',  'index')->name('loyaltypoints.index');
        Route::delete('/loyaltypoints-destroy/{id}',  'destroy')->name('loyaltypoints.destroy');
    });
    Route::controller(LoyaltyPointsController::class)->middleware(['admin','adminOrSubadmin:loyalty_points,add'])->group(function () {
        
        Route::get('/loyaltypoints-create',  'create')->name('loyaltypoints.create');
        Route::post('/loyaltypoints-store',  'store')->name('loyaltypoints.store');
       
    });
    Route::controller(LoyaltyPointsController::class)->middleware(['admin','adminOrSubadmin:loyalty_points,edit'])->group(function () {
        Route::get('/loyaltypoints-edit/{id}',  'edit')->name('loyaltypoints.edit');
        Route::post('/loyaltypoints-update/{id}',  'update')->name('loyaltypoints.update');
    });

    Route::controller(LoyaltyPointsController::class)->middleware(['admin','adminOrSubadmin:referal_links,view'])->group(function () {
        Route::get('/referals',  'referalindex')->name('referals.index');
        // Route::get('/referals-edit/{id}',  'referaledit')->name('referals.edit');
        // Route::post('/referals-update/{id}',  'referalupdate')->name('referals.update');
        Route::delete('/referals-destroy/{id}',  'referaldestroy')->name('referals.destroy');
    });

    Route::controller(LoyaltyPointsController::class)->middleware(['admin','adminOrSubadmin:referal_links,edit'])->group(function () {
        
        Route::get('/referals-edit/{id}',  'referaledit')->name('referals.edit');
        Route::post('/referals-update/{id}',  'referalupdate')->name('referals.update');
        
    });

    Route::post('/subadminActivate/{id}', [SubadminController::class, 'active'])->name('subadmin.activate');
    Route::post('/subadminDeactivate/{id}', [SubadminController::class, 'deactive'])->name('subadmin.deactivate');

    Route::controller(BookingController::class)->middleware(['admin','adminOrSubadmin:bookings'])->group(function () {
        Route::get('/booking',  'index')->name('booking.index');
        Route::get('/booking-create',  'create')->name('booking.create');
        Route::post('/booking-store',  'store')->name('booking.store');
        Route::get('/booking-edit/{id}',  'edit')->name('booking.edit');
        Route::post('/booking-update/{id}',  'update')->name('booking.update');
        Route::post('/booking/{id}/update-status',  'updateStatus')
        ->name('booking.update-status');
    
        Route::delete('/booking-destroy/{id}',  'destroy')->name('booking.destroy');
    });
    Route::controller(RequestBookingController::class)->middleware(['admin','adminOrSubadmin:requestbookings'])->group(function () {
        Route::get('/requestbooking',  'index')->name('requestbooking.index');
        // Route::get('/booking-create',  'create')->name('booking.create');
        // Route::post('/booking-store',  'store')->name('booking.store');
        Route::post('/requestbooking/{id}/edit',  'edit')->name('requestbooking.edit');
    //     Route::post('/requestbooking/update-status',  'updateStatus')
    // ->name('requestbooking.update-status');

        // Route::post('/requestbooking/{id}',  'update')->name('requestbooking.update');
        Route::delete('/requestbooking-destroy/{id}',  'destroy')->name('requestbooking.destroy');
    });

    Route::controller(DropoffController::class)->middleware(['admin','adminOrSubadmin:dropoff_requests'])->group(function () {
        Route::get('/dropoffrequest',  'index')->name('dropoffs.index');
        // Route::get('/booking-create',  'create')->name('booking.create');
        // Route::post('/booking-store',  'store')->name('booking.store');
        // Route::post('/requestbooking/{id}/edit',  'edit')->name('requestbooking.edit');
    //     Route::post('/requestbooking/update-status',  'updateStatus')
    // ->name('requestbooking.update-status');

        // Route::post('/requestbooking/{id}',  'update')->name('requestbooking.update');
        Route::delete('/dropoffrequest-destroy/{id}',  'destroy')->name('dropoffs.destroy');
    });    
    // Route::controller(ContactUsController::class)->middleware(['admin','adminOrSubadmin:ContactUs'])->group(function () {
    //     Route::get('/ContactUs',  'index')->name('ContactUs.index');
    //     Route::get('/ContactUs-create',  'create')->name('ContactUs.create');
    //     Route::post('/ContactUs-store',  'store')->name('ContactUs.store');
    //     Route::get('/ContactUs-edit/{id}',  'edit')->name('ContactUs.edit');
    //     Route::post('/ContactUs-update/{id}',  'update')->name('ContactUs.update');
    //     Route::delete('/ContactUs-destroy/{id}',  'destroy')->name('ContactUs.destroy');
    // });

    Route::controller(ContactUsController::class)
    ->middleware(['admin', 'adminOrSubadmin:ContactUs,view'])  // ✅ Restrict View Only
    ->group(function () {
        Route::get('/ContactUs', 'index')->name('ContactUs.index');
    });

Route::controller(ContactUsController::class)
    ->middleware(['admin', 'adminOrSubadmin:ContactUs,add'])  // ✅ Restrict Add Only
    ->group(function () {
        Route::get('/ContactUs-create', 'create')->name('ContactUs.create');
        Route::post('/ContactUs-store', 'store')->name('ContactUs.store');
    });

Route::controller(ContactUsController::class)
    ->middleware(['admin', 'adminOrSubadmin:ContactUs,edit'])  // ✅ Restrict Edit Only
    ->group(function () {
        Route::get('/ContactUs-edit/{id}', 'edit')->name('ContactUs.edit');
        Route::post('/ContactUs-update/{id}', 'update')->name('ContactUs.update');
    });
});








/*Team B routes
 * */








/*Team Candidate
 * */

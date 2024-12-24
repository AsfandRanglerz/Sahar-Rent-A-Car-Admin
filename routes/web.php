<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TeamAController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\SubadminController;
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
});

Route::post('/subadminActivate/{id}', [SubadminController::class, 'active'])->name('subadmin.activate');
Route::post('/subadminDeactivate/{id}', [SubadminController::class, 'deactive'])->name('subadmin.deactivate');



});








/*Team B routes
 * */








/*Team Candidate
 * */

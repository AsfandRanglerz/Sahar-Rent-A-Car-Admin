<?php

namespace App\Providers;

use App\Models\RequestBooking;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       // view()->share('ASSET',asset('public'));
        View::composer('admin.common.side_menu', function ($view) {
        $pickupCount = RequestBooking::whereNull('driver_id')
            ->whereNotNull('pickup_address')
            ->count();

        $dropoffCount = RequestBooking::whereNull('dropoff_driver_id')
            ->whereNotNull('dropoff_address')
            ->count();

        $view->with('totalCount', $pickupCount + $dropoffCount);
    });
       
    }
}

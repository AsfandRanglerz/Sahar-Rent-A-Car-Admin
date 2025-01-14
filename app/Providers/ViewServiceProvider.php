<?php

namespace App\Providers;

use App\Models\LicenseApproval;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('admin.common.side_menu', function ($view) {
            $pendingCount = LicenseApproval::where('action', 1)->count();
            $view->with('pendingCount', $pendingCount);
        });
    }
}

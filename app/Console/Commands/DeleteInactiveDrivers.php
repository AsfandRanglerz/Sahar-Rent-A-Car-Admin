<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Driver;
use App\Models\DeleteRequest;
use Illuminate\Console\Command;

class DeleteInactiveDrivers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:inactive-drivers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete driver accounts after 14 days if they remain offline';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString(); // Get current date
        $requests = DeleteRequest::whereDate('deactivation_date', '<=', Carbon::now()->subDays(14)->toDateString())->get();

        foreach ($requests as $request) {
            $driver = Driver::find($request->driver_id);

            if ($driver) {
                // Ensure the driver logged out within the 14-day period
                $logoutWithin14Days = $driver->logout_date >= $request->deactivation_date 
                                    && $driver->logout_date <= Carbon::now()->subDays(14)->toDateString();

            if ($driver && $driver->availability == 0) { // Only delete if driver is offline
                $driver->delete();
                $request->delete(); // Remove from delete_requests table
                $this->info("Deleted driver ID: {$driver->id}");
            }
        }
    }
        $this->info('Driver account deletion process completed.');
    }
}

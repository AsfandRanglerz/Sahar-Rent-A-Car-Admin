<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\DeleteRequest;
use Illuminate\Console\Command;

class DeleteInactiveCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:inactive-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete customer accounts after 14 days if they remain offline';

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
        $requests = DeleteRequest::whereDate('deactivation_date', '<=', Carbon::now()->addDays(14)->toDateString())->get();
        $this->info("Found " . $requests->count() . " requests for deletion.");
        foreach ($requests as $request) {
            $customer = User::find($request->user_id);

            if (!$customer) {
                $this->info("Customer not found for request ID: {$request->id}");
                continue; // Skip to the next request
            }

            if ($customer) {
                 // Calculate the logout deadline (14 days after deactivation)
                 $logoutDeadline = Carbon::parse($request->deactivation_date)->addDays(14)->toDateString();
$this->info("Checking customer ID: {$customer->id}, Deactivation Date: {$request->deactivation_date}, Logout Date: {$customer->logout_date}, Logout Deadline: {$logoutDeadline}, Availability: {$customer->availability}");
                // Ensure the customer logged out within the 14-day period
                $logoutWithin14Days = $customer->logout_date >= $request->deactivation_date 
                                    && $customer->logout_date <= $logoutDeadline;

            if ($customer->availability == 0 && $logoutWithin14Days) { // Only delete if customer is offline
                $customer->delete();
                $request->delete(); // Remove from delete_requests table
                $this->info("Deleted customer ID: {$customer->id}");
            }else {
                $this->info("❌ Customer ID {$customer->id} is still available (not offline).");
            }
        }
        else {
            $this->info("❌ Customer ID {$customer->id} did NOT log out within the required period.");
        }
    }
        $this->info('Customer account deletion process completed.');
    }
}

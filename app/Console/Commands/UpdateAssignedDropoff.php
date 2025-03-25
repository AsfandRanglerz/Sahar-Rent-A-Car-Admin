<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\RequestBooking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateAssignedDropoff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:assigned_dropoff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update assigned_dropoff field one day before dropoff_date';

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
        $currentDate = Carbon::today(); // Get today's date
    Log::info("Current Date: " . $currentDate->toDateString());

    $bookings = RequestBooking::where('assigned_dropoff', 0)->get();
    Log::info("Total Pending Bookings: " . $bookings->count());

    foreach ($bookings as $booking) {
        $dropOffDate = Carbon::parse($booking->dropoff_date);
        Log::info("Checking Booking ID: {$booking->id}, Dropoff Date: {$dropOffDate->toDateString()}");

        if ($currentDate->diffInDays($dropOffDate) === 1 && $currentDate->lessThan($dropOffDate)) {
            // Update assigned_dropoff
            $booking->update(['assigned_dropoff' => 1]);

            Log::info("✅ Updated booking ID: {$booking->id}, Dropoff Date: {$booking->dropoff_date}");
        }
    }

    Log::info("🔄 Dropoff update process completed.");
    $this->info('Assigned dropoff updated successfully.');
    }
}

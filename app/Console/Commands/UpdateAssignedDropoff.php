<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\RequestBooking;
use Illuminate\Console\Command;

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
    protected $description = 'Update assigned_dropoff field one day before dropoff_date and dropoff_time';

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
        $now = Carbon::now();
        $tomorrow = Carbon::now()->subDay();
        RequestBooking::where('assigned_dropoff', 0) // Only update unassigned drop-offs
            ->whereDate('dropoff_date', $tomorrow->toDateString()) // One day before dropoff_date
            ->update(['assigned_dropoff' => 1]);

        $this->info('Assigned dropoff updated successfully.');
    }
}

<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class DailyPayOutJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        DB::table('payments')->where('processed', false)->orderBy('customer_email') // ensure grouping works
            ->chunkById(1000, function ($payments) {
                $grouped = $payments->groupBy('customer_email');

                foreach ($grouped as $email => $records) {
                    SendInvoiceJob::dispatchSync($email, $records);

                    return false;

                }
            });

    }
}

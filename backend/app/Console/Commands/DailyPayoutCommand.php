<?php

namespace App\Console\Commands;

use App\Jobs\DailyPayOutJob;
use Illuminate\Console\Command;

class DailyPayoutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:payout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Invoices to customers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Processing');
        DailyPayOutJob::dispatch();
    }
}

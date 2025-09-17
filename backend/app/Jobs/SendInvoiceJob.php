<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $email,
        public $payments
    ) {}

    public function handle()
    {

        $data = [
            'payments' => $this->payments->toArray(),
            'totalUsd' => $this->payments->sum('usd_amount'),
        ];
        // dd($data);

        // Send email
        Mail::to($this->email)->send(new InvoiceMail($data));

        // Mark processed
        DB::table('payments')
            ->whereIn('id', $this->payments->pluck('id'))
            ->update(['processed' => true]);
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPaymentChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $validPayments) {}

    public function handle(): void
    {
        $pdo = DB::connection()->getPdo();
        $data = $this->validPayments;

        if (! empty($data)) {

            // Columns
            $columns = [
                'customer_id',
                'customer_name',
                'customer_email',
                'amount',
                'amount_usd',
                'currency',
                'reference_no',
                'date_time',
                'processed',
            ];

            $filtered = [];

            foreach ($data as $row) {
                $date = $row['date_time'] ?? null;

                // Validate date
                $validDate = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
                if ($validDate && $validDate->format('Y-m-d H:i:s') === $date) {
                    $filtered[] = $row; // valid rows
                } else {

                    Log::warning('Row is invalid : skipping', $row);
                }
            }

            if (empty($filtered)) {
                return; // nothing to insert
            }

            // construct placeholders
            $placeholders = rtrim(
                str_repeat('('.rtrim(str_repeat('?,', count($columns)), ',').'),', count($filtered)),
                ','
            );

            $sql = 'INSERT INTO payments ('.implode(',', $columns).') VALUES '.$placeholders;

            // Flatt the values
            $values = [];
            foreach ($filtered as $row) {
                foreach ($columns as $col) {
                    $values[] = $row[$col];
                }
            }

            // Run the statement
            $pdo->prepare($sql)->execute($values);
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\ExchangeRateService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;

class ProcessPaymentFileJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $path) {}

    /**
     * Execute the job.
     */
    public function handle(ExchangeRateService $service): void
    {
        //  Fetching the file if exists cancel
        if (! Storage::disk('s3')->exists($this->path)) {

            throw new \Exception('File does not exist');
        }
        $fullPath = Storage::disk('s3')->path($this->path);

        // Create the strean
        $rows = SimpleExcelReader::create($fullPath)->getRows();

        // Fetch the data from the service  or load from cahce
        $res = $service->fetchRates();

        // Array to fill it
        $validPayments = [];
        $chunkLimit = 1000;

        // Validate the payments
        $rows->each(
            function (array $rowProperties) use (&$validPayments, $service, $res, $chunkLimit) {
                // process each row

                $errors = $service->validateRow($rowProperties);

                if ($errors) {
                    Log::warning('Payment process failed ', [
                        'row' => $rowProperties,
                        'errors' => $errors,
                    ]);

                    return;
                }

                $validPayments[] = $service->convertToUsd($rowProperties, $res['rates']);

                // When the chunk becoms 1000 or greater dispatch this job
                if (count($validPayments) >= $chunkLimit) {

                    ProcessPaymentChunkJob::dispatch($validPayments)->onQueue('payments');

                    $validPayments = [];
                }
            }
        );

        if (count($validPayments)) {
            ProcessPaymentChunkJob::dispatch($validPayments)->onQueue('payments');
        }
    }
}

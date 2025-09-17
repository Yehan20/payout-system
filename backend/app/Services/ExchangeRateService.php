<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    /**
     * Create a new class instance.
     */
    private string $key = 'exchange_api';

    public function fetchRates()
    {

        // Check in cahce if this response exists
        if (Cache::has($this->key)) {

            return Cache::get($this->key);
        }

        // Send request
        $response = Http::withHeaders([
            'Content-Type' => 'text/plain',
            'apikey' => env('EXCHANGE_RATE_KEY'),

        ])->get('https://api.apilayer.com/exchangerates_data/latest', [
            'base' => 'USD',
        ]);

        // if success store in new cahce
        if ($response->successful()) {

            return $response->json(); // decode JSON automatically

        } else {
            // Handle failed API request
            throw new \Exception('Failed to fetch exchange rates');
        }
    }

    public function convertToUsd(&$payment, $rates)
    {

        $currency = $payment['currency'];

        if (isset($rates[$currency])) {
            $payment['amount_usd'] = round($payment['amount'] / $rates[$currency], 2);
        } else {
            $payment['amount_usd'] = null;
        }

        $payment['processed'] = 0;

        // Log this console
        Log::info('Payment ready for insertion', [
            'reference_no' => $payment['reference_no'],
            'customer_id' => $payment['customer_id'],
            'amount' => $payment['amount'],
        ]);

        return $payment;
    }

    public function validateRow(array $row): array
    {
        $errors = [];

        // Manual Validation perform better than facade

        if (empty($row['customer_id']) || strlen($row['customer_id']) > 40) {
            $errors[] = 'Invalid customer_id';
        }

        if (empty($row['customer_name']) || strlen($row['customer_name']) > 100) {
            $errors[] = 'Invalid customer_name';
        }

        if (empty($row['customer_email']) || ! filter_var($row['customer_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid customer_email';
        }

        if (! isset($row['amount']) || ! is_numeric($row['amount']) || $row['amount'] < 0) {
            $errors[] = 'Invalid amount';
        }

        if (empty($row['currency']) || strlen($row['currency']) !== 3) {
            $errors[] = 'Invalid currency';
        }

        if (
            empty($row['reference_no']) || strlen($row['reference_no']) > 50 ||
            ! preg_match('/^[A-Za-z0-9_-]+$/', $row['reference_no'])
        ) {
            $errors[] = 'Invalid reference_no';
        }

        if (empty($row['date_time']) || ! \DateTime::createFromFormat('Y-m-d H:i:s', $row['date_time'])) {
            $errors[] = 'Invalid date_time';
        }

        return $errors;
    }
}

<?php

use App\Http\Controllers\AuthController;
use App\Jobs\DailyPayOutJob;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('upload');
});

Route::post('login', [AuthController::class, 'store'])->middleware('throttle:api');
Route::post('logout', [AuthController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/mailable', function () {

    //     // Pick a random customer email that has payments
    //     $customerEmail = DB::table('payments')
    //         ->inRandomOrder()
    //         ->value('customer_email');

    //     if (!$customerEmail) {
    //         return 'No payments found';
    //     }

    //     // Get all payments for that customer
    //     $payments = DB::table('payments')
    //         ->where('customer_email', $customerEmail)
    //         ->get();

    //     // Format payments for the Mailable
    //     $formattedPayments = $payments->map(function ($payment) {
    //         return [
    //             'customer_id' => $payment->customer_id,
    //             'customer_name' => $payment->customer_name,
    //             'customer_email' => $payment->customer_email,
    //             'amount' => $payment->amount,
    //             'currency' => $payment->currency,
    //             'amount_usd' => $payment->amount_usd,
    //             'reference_no' => $payment->reference_no,
    //             'date_time' => $payment->date_time,
    //             'processed' => $payment->processed,
    //         ];
    //     })->toArray();

    //   //   dd($formattedPayments);

    //     // Calculate total USD
    //     $totalUsd = $payments->sum(fn($p) => $p->amount_usd ?? 0);

    //     $data = [
    //         'totalUsd' => $totalUsd,
    //         'payments' => collect($formattedPayments),
    //     ];
    //     // dd($data['payments']);

    //     Mail::to($customerEmail)->send(new InvoiceMail($data));

    DailyPayOutJob::dispatchSync();

    return 'mail sent';

    // Return Mailable for preview
    // return new InvoiceMail($data);
});

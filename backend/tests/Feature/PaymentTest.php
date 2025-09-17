<?php

namespace Tests\Feature;

use App\Jobs\SendInvoiceJob;
use App\Mail\InvoiceMail;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {

        parent::setUp();

        $this->user = $this->createUser();
    }

    public function test_returns_paginated_payment_list(): void
    {

        Payment::factory()->count(20)->create();

        $response = $this->actingAs($this->user)->getJson('api/payments');

        $response->assertHeader('Content-type', 'application/json')
            ->assertStatus(200)
            ->assertJsonCount(20, 'data');
    }

    public function test_invoice_mail_is_sent_to_user(): void
    {
        $paymentInfo = [
            'email' => 'zeus@example .com',
            'payments' => collect([
                [
                    'customer_id' => 1,
                    'customer_name' => 'Zeus',
                    'customer_email' => 'zeus@example.com',
                    'amount' => 100,
                    'currency' => 'USD',
                    'amount_usd' => 100,
                    'reference_no' => 'REF123',
                    'date_time' => now()->toDateTimeString(),
                    'processed' => true,
                ],
            ]),

        ];

        // Fake mail
        Mail::fake();

        // Dispatch job immediatly
        SendInvoiceJob::dispatchSync($paymentInfo['email'], $paymentInfo['payments']);

        // Check mail sent
        Mail::assertSent(InvoiceMail::class);

        // Check  "truth test".
        Mail::assertSent(function (InvoiceMail $mail) use ($paymentInfo) {

            return $mail->hasTo($paymentInfo['email']);
        });
    }

    private function createUser(): User
    {

        return User::factory()->create([
            'is_admin' => true,
        ]);
    }
}

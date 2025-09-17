<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Helvetica, sans-serif;
            background: #f4f5f6;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 12px;
            padding: 24px;
        }

        .payment {
            border: 1px solid #0867ec;
            border-radius: 8px;
            margin-bottom: 16px;
            padding: 12px;
            background: #f0f7ff;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .label {
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: black;
            margin-top: 24px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 style="text-align:center;">Payments Summary</h2>
        <p><strong>Total $(USD):</strong> {{ $data['totalUsd'] }}</p>

        @foreach ($data['payments'] as $payment)
            <div class="payment">
                <div class="payment-row"><span class="label">ID:</span><span>{{ $payment->customer_id }}</span></div>
                <div class="payment-row"><span class="label">Name:</span><span>{{ $payment->customer_name }}</span></div>
                <div class="payment-row"><span class="label">Email:</span><span>{{ $payment->customer_email }}</span>
                </div>
                <div class="payment-row"><span
                        class="label">Amount:</span><span>{{ number_format($payment->amount, 2) }}</span></div>
                <div class="payment-row"><span class="label">Currency:</span><span>{{ $payment->currency }}</span>
                </div>
                <div class="payment-row"><span class="label">Amount
                        (USD)
                        :</span><span>{{ $payment->amount_usd ? number_format($payment->amount_usd, 2) : '-' }}</span>
                </div>
                <div class="payment-row"><span class="label">Reference
                        No:</span><span>{{ $payment->reference_no ?? '-' }}</span></div>
                <div class="payment-row"><span class="label">Date Time:</span><span>{{ $payment->date_time }}</span>
                </div>
                <div class="payment-row"><span
                        class="label">Processed:</span><span>{{ $payment->processed ? 'Yes' : 'No' }}</span></div>
            </div>
        @endforeach

        <div class="footer">
            Thanks,<br>
            {{ config('app.name') }}
        </div>

    </div>
</body>

</html>

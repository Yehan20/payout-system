<!DOCTYPE html>
<html>
<head>
    <style>
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #ddd; padding: 8px; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Daily Invoice</h2>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference No</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Amount (USD)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment['date_time'] }}</td>
                    <td>{{ $payment['reference_no'] }}</td>
                    <td>{{ $payment['amount'] }}</td>
                    <td>{{ $payment['currency'] }}</td>
                    <td>{{ $payment['usd_amount'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total (USD):</strong> {{ $total }}</p>
</body>
</html>

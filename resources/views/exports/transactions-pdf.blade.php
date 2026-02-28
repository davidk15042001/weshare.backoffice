<!DOCTYPE html>
<html>
<head>
    <title>Transactions Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>

<h2>Transactions Report</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Plan</th>
            <th>Code</th>
            <th>Gateway</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->name ?? optional($transaction->user)->name }}</td>
                <td>{{ $transaction->plan_id }}</td>
                <td>{{ $transaction->transaction_code }}</td>
                <td>{{ $transaction->gateway }}</td>
                <td>{{ $transaction->amount }}</td>
                <td>{{ $transaction->status }}</td>
                <td>{{ $transaction->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>

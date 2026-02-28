<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(public $transactions)
    {
    }
    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'ID',
            'User Name',
            'User ID',
            'Plan ID',
            'Transaction Code',
            'Transaction Source',
            'Gateway',
            'Gateway Reference',
            'Amount',
            'Quantity',
            'VAT',
            'Tax',
            'Currency',
            'Status',
            'Narration',
            'IP Address',
            'Created At',
        ];
    }

    public function map($transaction): array
    {
        $meta = json_decode($transaction->meta, true);

        return [
            $transaction->id,
            $transaction->name ?? optional($transaction->user)->name,
            $transaction->user_id,
            $transaction->plan_id,
            $transaction->transaction_code,
            $transaction->transaction_source,
            $transaction->gateway,
            $transaction->gateway_reference,
            $transaction->amount,
            $transaction->quantity,
            $transaction->vat,
            $transaction->tax,
            $transaction->currency,
            $transaction->status,
            $transaction->narration,
            $meta['ip'] ?? null,
            $transaction->created_at,
        ];
    }
}

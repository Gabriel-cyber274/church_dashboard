<?php

namespace App\Exports;

use App\Models\Withdrawal;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WithdrawalsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Withdrawal::with(['program', 'project']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Program',
            'Project',
            'Amount',
            'Status',
            'Withdrawal Date',
            'Description',
            'Created At',
        ];
    }

    public function map($withdrawal): array
    {
        return [
            $withdrawal->id,
            $withdrawal->program?->name,
            $withdrawal->project?->name,
            $withdrawal->amount,
            $withdrawal->status,
            optional($withdrawal->withdrawal_date)->format('Y-m-d'),
            $withdrawal->description,
            $withdrawal->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}

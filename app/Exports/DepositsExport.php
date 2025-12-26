<?php

namespace App\Exports;

use App\Models\Deposit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DepositsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Deposit::with(['member', 'program', 'project']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Member',
            'Program',
            'Project',
            'Amount',
            'Status',
            'Deposit Date',
            'Description',
            'Created At',
        ];
    }

    public function map($deposit): array
    {
        return [
            $deposit->id,
            $deposit->member?->full_name,
            $deposit->program?->name,
            $deposit->project?->name,
            $deposit->amount,
            $deposit->status,
            optional($deposit->deposit_date)->format('Y-m-d'),
            $deposit->description,
            $deposit->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}

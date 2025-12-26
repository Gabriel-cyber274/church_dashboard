<?php

namespace App\Exports;

use App\Models\Tithe;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TithesExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Tithe::with(['program']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Program',
            'Amount',
            'Status',
            'Tithe Date',
            'Description',
            'Created At',
        ];
    }

    public function map($tithe): array
    {
        return [
            $tithe->id,
            $tithe->program?->name,
            $tithe->amount,
            $tithe->status,
            optional($tithe->tithe_date)->format('Y-m-d'),
            $tithe->description,
            $tithe->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\Offering;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OfferingsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Offering::with(['program']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Program',
            'Amount',
            'Status',
            'Offering Date',
            'Description',
            'Created At',
        ];
    }

    public function map($offering): array
    {
        return [
            $offering->id,
            $offering->program?->name,
            $offering->amount,
            $offering->status,
            optional($offering->offering_date)->format('Y-m-d'),
            $offering->description,
            $offering->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}

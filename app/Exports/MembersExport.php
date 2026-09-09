<?php

namespace App\Exports;

use App\Models\Member;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MembersExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Member::query()
            ->with('departments')
            ->orderBy('id');
    }

    public function headings(): array
    {
        return [
            'ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone Number',
            'Date of Birth',
            'Gender',
            'Marital Status',
            'Country',
            'State',
            'City',
            'Address',
            'Departments',
            'Created At',
        ];
    }

    public function map($member): array
    {
        return [
            $member->id,
            $member->first_name,
            $member->last_name,
            $member->email,
            $member->phone_number,
            $member->date_of_birth
                ? Carbon::parse($member->date_of_birth)->format('Y-m-d')
                : null,
            $member->gender,
            $member->marital_status,
            $member->country,
            $member->state,
            $member->city,
            $member->address,
            $member->departments->pluck('name')->implode(', '),
            $member->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'bank_name' => 'First Bank of Nigeria',
                'account_number' => '1234567890',
                'account_holder_name' => 'John Doe',
            ],
            [
                'bank_name' => 'Guaranty Trust Bank',
                'account_number' => '2345678901',
                'account_holder_name' => 'Jane Smith',
            ],
            [
                'bank_name' => 'Access Bank',
                'account_number' => '3456789012',
                'account_holder_name' => 'Michael Johnson',
            ],
            [
                'bank_name' => 'Zenith Bank',
                'account_number' => '4567890123',
                'account_holder_name' => 'Mary Williams',
            ],
        ];

        foreach ($banks as $bank) {
            Bank::create($bank);
        }
    }
}

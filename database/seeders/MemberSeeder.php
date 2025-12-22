<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('members')->insert([
            [
                'first_name'     => 'John',
                'last_name'      => 'Doe',
                'email'          => 'john.doe@example.com',
                'date_of_birth'  => '1995-04-12',
                'address'        => '123 Main Street, Lagos',
                'phone_number'   => '+2348012345678',
                'gender'         => 'male',
                'marital_status' => 'single',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'first_name'     => 'Jane',
                'last_name'      => 'Smith',
                'email'          => 'jane.smith@example.com',
                'date_of_birth'  => '1992-09-22',
                'address'        => '45 Allen Avenue, Ikeja',
                'phone_number'   => '+2348098765432',
                'gender'         => 'female',
                'marital_status' => 'married',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Williams',
                'email' => 'emily.williams@example.com',
                'date_of_birth' => '1992-03-10',
                'address' => '321 Elm Street, Houston, TX 77001',
                'phone_number' => '+1 (555) 789-0123',
                'gender' => 'Female',
                'marital_status' => 'Married',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael.brown@example.com',
                'date_of_birth' => '1982-07-25',
                'address' => '654 Maple Drive, Phoenix, AZ 85001',
                'phone_number' => '+1 (555) 234-5678',
                'gender' => 'Male',
                'marital_status' => 'Single',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Davis',
                'email' => null,
                'date_of_birth' => '1995-12-05',
                'address' => '987 Cedar Lane, Philadelphia, PA 19101',
                'phone_number' => '+1 (555) 345-6789',
                'gender' => 'Female',
                'marital_status' => 'Single',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'first_name'     => 'Michael',
                'last_name'      => 'Brown',
                'email'          => null, // allowed
                'date_of_birth'  => null,
                'address'        => null,
                'phone_number'   => null,
                'gender'         => null,
                'marital_status' => null,
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
        ]);
    }
}

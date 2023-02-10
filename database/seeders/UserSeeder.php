<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Eki Ramadhan',
            'email' => 'ramadhaneki91@gmail.com',
            'password' => Hash::make('123456'),
            'address' => 'JL. Jalan Jalan',
            'houseNumber' => 'Rumah No 5A',
            'phoneNumber' => '082262554213',
            'city' => 'purwakarta',
            'roles' => 'ADMIN'
        ]);
    }
}

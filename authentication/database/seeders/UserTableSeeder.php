<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('admin@123456'),
            'role' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'user@user.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('user@123456'),
            'role' => 0,
        ]);
    }
}

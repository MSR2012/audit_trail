<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    private const ROLE_USER = 1;
    private const ROLE_ADMIN = 2;

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
            'role' => self::ROLE_ADMIN,
        ]);

        DB::table('users')->insert([
            'name' => 'User',
            'email' => 'user@user.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('user@123456'),
            'role' => self::ROLE_USER,
        ]);

        DB::table('users')->insert([
            'name' => 'User2',
            'email' => 'user2@user.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('user2@123456'),
            'role' => self::ROLE_USER,
        ]);
    }
}

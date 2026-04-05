<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $total = 10000;
        $chunkSize = 1000;
        $password = Hash::make('password');

        for ($i = 0; $i < $total; $i += $chunkSize) {
            $users = [];
            for ($j = 0; $j < $chunkSize; $j++) {
                $id = $i + $j + 1;
                $users[] = [
                    'name' => 'User ' . $id,
                    'email' => 'user' . $id . '@example.com',
                    'password' => $password,
                    'role' => 'viewer',
                    'status' => true,
                    'must_change_password' => false,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('users')->insert($users);
        }
    }
}

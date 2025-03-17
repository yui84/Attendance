<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
        $param = [
            'role_id' => 1,
            'name' => '管理者ユーザー',
            'email' => 'admin@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin1234'),
            'created_at' => now(),
            'updated_at' => now()
        ];
        User::create($param);

        $param = [
            'name' => '一般ユーザー',
            'email' => 'general1@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ];
        User::create($param);
    }
}

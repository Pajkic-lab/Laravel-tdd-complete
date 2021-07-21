<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => Str::random(10),
            'email' => 'admin@test.com',
            'password' => Hash::make('Pass.123'),
        ]);
        $user->assignRole('admin');

        $user = User::create([
            'name' => Str::random(10),
            'email' => 'user@test.com',
            'password' => Hash::make('Pass.123'),
        ]);
        $user->assignRole('user');
    }
}

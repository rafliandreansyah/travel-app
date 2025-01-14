<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => 'rafli andreansyah',
            'email' => 'rafli@gmail.com',
            'password' => Hash::make('amaterasu'),
            'phone_number' => '6281232720821',
            'role' => 'admin',
            'active' => true
        ]);
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => 'azha',
            'email' => 'azha@gmail.com',
            'password' => Hash::make('amaterasu'),
            'phone_number' => '6281232720111',
            'role' => 'user',
            'active' => true
        ]);
        User::factory()->count(300)->create();
    }
}

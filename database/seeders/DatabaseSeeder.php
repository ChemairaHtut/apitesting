<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        \App\Models\Admin::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '09254208419'
        ]);
        // \App\Models\Admin::factory(10)->create();
        // DB::table('admins')->insert([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'phone' => '09254208419',
        //     'password' => Hash::make('password')
        // ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Admin::firstOrCreate(
            ['email' => 'admin@parkingrental.com'],
            ['name' => 'Admin', 'password' => bcrypt('Admin@1234')]
        );
    }
}

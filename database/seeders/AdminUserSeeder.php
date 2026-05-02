<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::updateOrCreate([
            'email' => 'student@example.com',
        ], [
            'name' => 'Student User',
            'username' => 'student',
            'password' => Hash::make('student123'),
            'role' => 'student',
        ]);

        User::updateOrCreate([
            'email' => 'steven@gmail.com',
        ], [
            'name' => 'Steven Edward Barba',
            'username' => 'steven',
            'password' => Hash::make('steven123'),
            'role' => 'professor',
        ]);

        User::updateOrCreate([
            'email' => 'gusion@gmail.com',
        ], [
            'name' => 'Gusion Bai',
            'username' => 'gusion',
            'password' => Hash::make('gusion123'),
            'role' => 'student',
        ]);
    }
}

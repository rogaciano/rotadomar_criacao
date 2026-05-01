<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = env('ADMIN_SEED_PASSWORD') ?: Str::random(20);

        User::firstOrCreate(
            ['email' => env('ADMIN_SEED_EMAIL', 'admin@rotadomar.com')],
            [
                'name' => 'Admin',
                'password' => Hash::make($password),
                'is_admin' => true,
            ]
        );

        if (!env('ADMIN_SEED_PASSWORD') && $this->command) {
            $this->command->warn("Admin password gerado: {$password}");
        }
    }
}

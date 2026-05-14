<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (!$email || !$password) {
            $this->command->error('Please set ADMIN_EMAIL and ADMIN_PASSWORD in your .env file.');
            return;
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'password' => Hash::make($password),
                'name' => 'Admin'
            ]);
            $this->command->info('Admin user updated.');
        } else {
            User::create([
                'name' => 'Admin',
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);
            $this->command->info('Admin user created successfully.');
        }
    }
}

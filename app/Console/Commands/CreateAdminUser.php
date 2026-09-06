<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'make:admin {email} {password}';
    protected $description = 'Create an admin user';

    public function handle(): void
    {
        $user = User::updateOrCreate(
            ['email' => $this->argument('email')],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make($this->argument('password')),
            ]
        );

        $this->info("Admin user created: {$user->email}");
    }
}

//php artisan make:admin admin@example.com secret123
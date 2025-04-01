<?php

namespace App\Console\Commands;

use App\Models\User\User;
use Illuminate\Console\Command;

class CreateAdminUserCommand extends Command
{
    protected $signature = 'admin:create {username?} {email?}';

    protected $description = 'Create an admin user';

    public function handle(): int
    {
        $username = $this->argument('username') ?? $this->ask('Enter username');
        $email = $this->argument('email') ?? $this->ask('Enter email');
        $password = $this->secret('Enter password');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $username,
                'password' => $password,
            ]
        );

        $user->forceFill([
            'is_admin' => true,
        ])->save();

        $this->info("Admin user {$username} created successfully!");

        return self::SUCCESS;
    }
}

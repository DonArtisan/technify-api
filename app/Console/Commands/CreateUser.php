<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    protected $signature = 'technify:create-admin-user';

    protected $description = 'Adds a new user admin to the system.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        /** @var array<string, string> $details */
        $details = $this->getDetails();

        $details['password'] = bcrypt($details['password']);
        unset($details['confirm_password']);

        /** @var User $user */
        $user = User::query()->updateOrCreate(['email' => $details['email']], $details);

        $user->assign('admin');

        return 0;
    }

    /**
     * Ask for user details.
     *
     * @return array<string, mixed>
     */
    private function getDetails(): array
    {
        $details['first_name'] = $this->ask('First Name');
        $details['last_name'] = $this->ask('Last Name');
        $details['email'] = $this->ask('Email');
        $details['email_verified_at'] = now();
        $details['is_admin'] = true;

        $details['password'] = $this->secret('Password').'';
        $details['confirm_password'] = $this->secret('Confirm password').'';

        /** @var array<string, string> $details */
        while (! $this->isMatch($details['password'], $details['confirm_password'])) {
            $this->error('Password and Confirm password do not match. Make them match.');

            $details['password'] = $this->secret('Password').'';
            $details['confirm_password'] = $this->secret('Confirm password').'';
        }

        return $details;
    }

    /**
     * Check if password and confirm password matches.
     */
    private function isMatch(string $password, string $confirmPassword): bool
    {
        return $password === $confirmPassword;
    }
}

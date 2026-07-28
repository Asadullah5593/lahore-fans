<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

/**
 * Create an admin account or reset an existing one from the command line.
 *
 * The admin panel itself is the normal place to manage users, but that assumes
 * you can already sign in. This command is the way back in when nobody can —
 * run it over SSH or the cPanel terminal on the server.
 */
class UpsertUserCommand extends Command
{
    protected $signature = 'user:upsert
                            {email : Email address of the account}
                            {--name= : Display name (defaults to the part before @ when creating)}
                            {--password= : Password to set (a strong one is generated when omitted)}';

    protected $description = 'Create an admin user, or reset an existing user\'s password';

    public function handle(): int
    {
        $email = trim((string) $this->argument('email'));
        $user = User::firstWhere('email', $email);

        $password = (string) ($this->option('password') ?: '');
        $generated = $password === '';

        if ($generated) {
            // Str::password() produces a mixed-case, numeric, symbol-bearing string.
            $password = Str::password(20);
        }

        $validator = Validator::make(
            ['email' => $email, 'password' => $password],
            [
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', Password::min(12)->mixedCase()->numbers()->symbols()],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        if ($user) {
            $user->forceFill(['password' => $password])->save();
            $action = 'Password reset for existing user';
        } else {
            $name = (string) ($this->option('name') ?: Str::headline(Str::before($email, '@')));

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ]);

            $action = 'User created';
        }

        $this->newLine();
        $this->warn('==================================================');
        $this->warn(" {$action} — save these credentials now:");
        $this->warn("   Name:     {$user->name}");
        $this->warn("   Email:    {$user->email}");
        $this->warn("   Password: {$password}");
        $this->warn('==================================================');
        $this->newLine();

        if ($generated) {
            $this->line('This password was generated and is not stored anywhere in readable form.');
        }

        return self::SUCCESS;
    }
}

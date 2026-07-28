<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => 'admin@lahorefan.com']);

        if (! $admin->exists) {
            $password = Str::password(16);
            $admin->fill([
                'name' => 'Lahore Fan Admin',
                'password' => Hash::make($password),
            ])->save();

            $this->command->newLine();
            $this->command->warn('==================================================');
            $this->command->warn(' Admin account created — save these credentials:');
            $this->command->warn('   Email:    admin@lahorefan.com');
            $this->command->warn("   Password: {$password}");
            $this->command->warn('==================================================');
            $this->command->newLine();
        } else {
            $this->command->info('Admin user already exists — password left unchanged.');
        }

        // The real dealer network. OutletSeeder is the original demo/placeholder
        // data and is deliberately no longer called.
        $this->call(DealerSeeder::class);
    }
}

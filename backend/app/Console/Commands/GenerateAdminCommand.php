<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class GenerateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if (User::where('email', $email)->exists()) {

            $this->error('Email already taken.');

            return 1;
        }

        $user = User::create([
            'email' => $email,
            'name' => $this->argument('name'),
            'password' => Hash::make($this->argument('password')),
            'is_admin' => true,
        ]);

        $this->info("Admin user created Email:{$user->email} Password:{$this->argument('password')}");
    }
}

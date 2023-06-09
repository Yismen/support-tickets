<?php

namespace Dainsys\Support\Console\Commands;

use Illuminate\Console\Command;

class CreateSuperUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:create-super-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert a current user into a support super user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->ask('Please provide the email of the user to be made support super admin!');
        $user = \App\Models\User::where('email', $email)->first();

        if (!$user) {
            $this->warn('No user found with this email. Please provide a valid user');

            return self::FAILURE;
        }

        if (!$user->isSupportSuperAdmin()) {
            $user->supportSuperAdmin()->create();
        }

        $this->info("User {$user->name} is now a Support Super Admin user!");

        return self::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'link:create-admin {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set user as admin by steamId64.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::where('steam_id', $this->argument('user'))->first();

        $user->assignRole('admin');
        return Command::SUCCESS;
    }
}

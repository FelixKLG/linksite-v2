<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'link:create-token {user?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create API token for user by steamId64.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->arguments('user') ?? this->ask('user');
        $user = User::where('steam_id', $this->argument('user'))->first();

        $token = $user->createToken('CLI Created Token');
        $this->info($token->plainTextToken);
        return Command::SUCCESS;
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate([
            'steam_id' => 76561198250930688
        ], [
                'name' => 'FelixKLG',
                'discord_id' => 270150443512889344,
                'gmod_store_id' => 'de37018f-b7b0-4055-9fd7-9ec03f3ac899',
                'avatar' => 'https://avatars.akamai.steamstatic.com/1f1802babee511aab9a7ba0fbd5673fd9363967c_medium.jpg'
        ]);
    }
}

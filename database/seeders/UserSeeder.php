<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate([
            'name' => 'FelixKLG',
            'steamId' => 76561198250930688,
            'discordId' => 270150443512889344,
            'gmodStoreId' => 'de37018f-b7b0-4055-9fd7-9ec03f3ac899',
            'avatar' => 'https://avatars.akamai.steamstatic.com/1f1802babee511aab9a7ba0fbd5673fd9363967c_medium.jpg'
            ]);
    }
}

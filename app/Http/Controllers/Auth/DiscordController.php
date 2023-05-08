<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class DiscordController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('discord')->setScopes(['identify'])->redirect();
    }

    public function callback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect()->route('home');
        }

        $discordUser = Socialite::driver('discord')->user();

        if (!User::where('discord_id', $discordUser->id)->exists()) {
            $request->user()->update(['discord_id' => $discordUser->id]);
        } else {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home');
        }

        $this->notification();
        $this->assignRole();

        return redirect()->route('linked');
    }

    public function assignRole()
    {
        $discordToken = config('services.discord.api_token');
        $guildId = config('services.discord.guild_id');
        $roleId = config('services.discord.role_id');

        $user = Auth::user();

        return Http::withToken($discordToken, 'Bot')
            ->put("https://discord.com/api/guilds/$guildId/members/$user->discord_id/roles/$roleId");
    }

    public function notification()
    {
        $discordWebhook = config('services.discord.webhook_url');
        $user = Auth::user();

        return Http::post($discordWebhook, [
            'embeds' => [
                [
                    'title' => "Account Created",
                    'description' => "A new account has been created.",
                    'url' => "https://gmodstore.com/users/$user->steam_id",
                    'color' => '7506394',
                    'fields' => [
                        [
                            'name' => 'Discord Account',
                            'value' => "<@$user->discord_id>",
                        ], [
                            'name' => 'Discord ID',
                            'value' => $user->discord_id,
                        ],
                        [
                            'name' => 'SteamID64',
                            'value' => $user->steam_id,
                        ]
                    ],
                    'thumbnail' => [
                        'url' => $user->avatar
                    ],
                    'footer' => [
                        'text' => 'Ley\'s Discord Link',
                        'icon_url' => 'https://leystryku.support/img/icon-anim.gif',
                        'inline' => true
                    ]
                ]
            ],
        ])->throw();
    }
}

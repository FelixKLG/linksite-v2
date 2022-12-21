<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SteamController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('steam')->redirect();
    }

    public function callback()
    {
        $steamUser = Socialite::driver('steam')->user();

        $user = User::firstOrCreate([
            'steam_id' => $steamUser->getId(),
        ], [
            'avatar' => $steamUser->getAvatar(),
            'name' => $steamUser->getNickname()
        ]);

        Auth::login($user);

        $user->gmodStoreId;

        if ($user->updated_at->diffInDays(Carbon::now()) > 1) {
            $user->update([
                'avatar' => $steamUser->getAvatar(),
                'name' => $steamUser->getNickname()
            ]);
        }

        if (!$user->discordId) {
            return redirect()->route('auth.discord');
        } else {
            return redirect()->route('home');
        }
    }
}

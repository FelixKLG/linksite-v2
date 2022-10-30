<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function get(User $user) {
        return JsonResource::make($user);
    }

    public function delete(User $user) {
        $user->delete();
        return response()->noContent(204);
    }

    public function list() {
        return Cache::remember("users.list", now()->addMinutes(5), function() {
            return JsonResource::collection(User::all());
        });
    }

    public function purchases(User $user) {
         return JsonResource::collection($user->purchases);
    }

    public function me(Request $request)
    {
        return JsonResource::make($request->user());
    }
}

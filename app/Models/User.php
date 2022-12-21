<?php

namespace App\Models;

use Http;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, HasUuids;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'steam_id',
        'discord_id',
        'gmod_store_id',
        'avatar',
    ];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';
    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    public function getGmodStoreIdAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $response = Http::withToken(config('services.gmodstore.api_token'))
            ->get('https://www.gmodstore.com/api/v3/users/', [
                'filter[steamId]' => $this->steam_id
            ]);
        $profiles = $response->json('data');

        if (count($profiles) > 0) {
            $this->update([
                'gmod_store_id' => $profiles[0]['id']
            ]);
            return $profiles[0]['id'];
        }
    }

    public function getPurchasesAttribute()
    {
        return Cache::remember("gms_purchases_$this->id", now()->addMinutes(20), function () {
            $httpResponse = Http::withToken(config('services.gmodstore.api_token'))
                ->get("https://www.gmodstore.com/api/v3/users/$this->gmod_store_id/purchases", [
                    "perPage" => 100
                ])->json();

            $data = collect($httpResponse['data']);

            $ids = $data->filter(function ($purchase) {
                return !$purchase['revoked'];
            })->map(function ($purchase) {
                return $purchase['productId'];
            });

            return [
                "purchases" => $ids,
            ];
        });
    }
}

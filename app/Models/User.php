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

    public function isSetupComplete(): bool {
        if ($this->steam_id && $this->discord_id) {
            return true;
        } else {
            return false;
        }
    }

    public function getPurchasesAttribute()
    {
        $user_id = $this->attributes["uuid"];
        return Cache::remember("gms_purchases_$user_id", now()->addMinutes(20), function () {
            $httpResponse = Http::withToken(config('services.gmodstore.api_token'))
                ->get("https://www.gmodstore.com/api/v3/users/$this->gmod_store_id/purchases", [
                    "perPage" => 25,
                    "filter[revoked]" => false,
                    "filter[productId][]" => [
                        "6c5e862b-3dcf-4769-aa6b-8a001937c56b", // LSAC
                        "773f2482-b72d-47cf-977b-bf6ac3653b57", // SwiftAC
                        "244bfe2d-f39e-4adc-86eb-d4b7cbb1af2f", // Hitreg
                        "60d1fabc-9afe-44b1-81b1-81f234f4e80f", // Screengrabs
                        "c2ed35d6-db31-4a76-bf48-bdad343f2ff3", // WorkshopDL
                        "5ed5f156-858a-4e6a-8b9a-0f926cdcf09b", // SexyErrors
                    ],
                ])->json();

            $data = collect($httpResponse['data']);

            $ids = $data->map(function ($purchase) {
                return $purchase['productId'];
            });

            return [
                "data" => [
                    "LSAC" => $ids->contains("6c5e862b-3dcf-4769-aa6b-8a001937c56b"),
                    "SwiftAC" => $ids->contains("773f2482-b72d-47cf-977b-bf6ac3653b57"),
                    "HitReg" => $ids->contains("244bfe2d-f39e-4adc-86eb-d4b7cbb1af2f"),
                    "ScreenGrabs" => $ids->contains("60d1fabc-9afe-44b1-81b1-81f234f4e80f"),
                    "WorkshopDL" => $ids->contains("c2ed35d6-db31-4a76-bf48-bdad343f2ff3"),
                    "SexyErrors" => $ids->contains("5ed5f156-858a-4e6a-8b9a-0f926cdcf09b"),
                ],
            ];
        });
    }
}

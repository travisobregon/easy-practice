<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Sushi\Sushi;

class Client extends Model
{
    use HasFactory;
    use Sushi;

    public function getRows(): array
    {
        return Cache::remember('clients', Date::now()->addHour(), function () {
            return $this->fetchClients();
        });
    }

    public function fetchClients(): array
    {
        $response = Http::withToken(Config::get('services.easy-practice.token'))
            ->acceptJson()
            ->asJson()
            ->get('https://system.easypractice.net/api/v1/clients');

        return Collection::make($response->json('data'))->map(function ($client) {
            return Arr::only($client, [
                'id',
                'name',
                'email',
                'date_of_birth',
                'profile_image_url',
                'created_at',
                'updated_at',
            ]);
        })->toArray();
    }
}

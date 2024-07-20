<?php

namespace App\Models;

use App\Http\Integrations\EasyPractice\EasyPracticeConnector;
use App\Http\Integrations\EasyPractice\Requests\GetClientsRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Sushi\Sushi;

class Client extends Model
{
    use HasFactory;
    use Sushi;

    public function getRows(): array
    {
        $easyPractice = new EasyPracticeConnector;
        $request = new GetClientsRequest;

        return $easyPractice->send($request)->collect('data')->map(function ($client) {
            return Arr::only($client, [
                'id',
                'name',
                'email',
                'date_of_birth',
                'profile_image_url',
                'status',
                'created_at',
                'updated_at',
            ]);
        })->toArray();
    }
}

<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Http\Integrations\EasyPractice\EasyPracticeConnector;
use App\Http\Integrations\EasyPractice\Requests\CreateClientRequest;
use App\Http\Integrations\EasyPractice\Requests\GetClientsRequest;
use App\Models\Client;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $easyPractice = new EasyPracticeConnector();
        $request = new CreateClientRequest($data);

        $easyPractice->send($request);

        $easyPractice->invalidateCache();
        $easyPractice->send(new GetClientsRequest);

        return new Client($data);
    }
}

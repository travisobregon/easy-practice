<?php

use App\Filament\Resources\ClientResource\Pages\ListClients;
use App\Http\Integrations\EasyPractice\Requests\GetClientsRequest;
use App\Models\Client;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

use function Pest\Livewire\livewire;

beforeEach(function () {
    MockClient::global([
        GetClientsRequest::class => MockResponse::fixture('clients'),
    ]);
});

it('can render page', function () {
    livewire(ListClients::class)->assertSuccessful();
});

it('can render columns', function () {
    livewire(ListClients::class)
        ->assertCanRenderTableColumn('name')
        ->assertCanRenderTableColumn('email')
        ->assertCanRenderTableColumn('date_of_birth')
        ->assertCanRenderTableColumn('profile_image_url')
        ->assertCanRenderTableColumn('status');
});

it('can display clients', function () {
    $clients = Client::query()->get();

    livewire(ListClients::class)
        ->assertCanSeeTableRecords($clients)
        ->assertCountTableRecords(2);
});

it('can search clients by name', function () {
    $clients = Client::query()->get();
    $name = $clients->first()->name;

    livewire(ListClients::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords($clients->where('name', $name))
        ->assertCanNotSeeTableRecords($clients->where('name', '!=', $name));
});

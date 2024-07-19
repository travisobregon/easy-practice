<?php

use App\Filament\Resources\ClientResource\Pages\ListClients;
use App\Models\Client;
use function Pest\Livewire\livewire;

it('can render page', function () {
    livewire(ListClients::class)->assertSuccessful();
});

it('can render columns', function () {
    livewire(ListClients::class)
        ->assertCanRenderTableColumn('name')
        ->assertCanRenderTableColumn('email')
        ->assertCanRenderTableColumn('date_of_birth')
        ->assertCanRenderTableColumn('profile_image_url');
});

it('can display clients', function () {
    $clients = Client::factory()->count(2)->create();

    livewire(ListClients::class)
        ->assertCanSeeTableRecords($clients);
});

it('can search clients by name', function () {
    $clients = Client::factory()->count(2)->create();

    $name = $clients->first()->name;

    livewire(ListClients::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords($clients->where('name', $name))
        ->assertCanNotSeeTableRecords($clients->where('name', '!=', $name));
});

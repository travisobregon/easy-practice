<?php

use App\Filament\Resources\ClientResource\Pages\CreateClient;
use App\Http\Integrations\EasyPractice\Requests\CreateClientRequest;
use App\Http\Integrations\EasyPractice\Requests\GetClientsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

use function Pest\Livewire\livewire;

it('can render page', function () {
    livewire(CreateClient::class)->assertSuccessful();
});

it('can create', function () {
    $mockClient = MockClient::global([
        GetClientsRequest::class => MockResponse::fixture('clients/index'),
        CreateClientRequest::class => MockResponse::fixture('clients/create'),
    ]);

    $state = [
        'name' => fake()->name,
        'email' => fake()->email,
        'date_of_birth' => fake()->date(),
        'profile_image_url' => fake()->imageUrl,
        'status' => fake()->randomElement(['active', 'inactive']),
    ];

    livewire(CreateClient::class)
        ->fillForm($state)
        ->call('create')
        ->assertHasNoFormErrors();

    $mockClient->assertSentInOrder([
        fn (CreateClientRequest $request) => $request->body()->all() === $state,
        GetClientsRequest::class,
    ]);
});

it('can validate input', function ($field) {
    livewire(CreateClient::class)
        ->fillForm([$field => null])
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
})->with([
    'name',
    'email',
    'date_of_birth',
    'profile_image_url',
    'status',
]);

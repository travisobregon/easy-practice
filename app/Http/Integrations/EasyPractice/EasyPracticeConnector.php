<?php

namespace App\Http\Integrations\EasyPractice;

use Illuminate\Support\Facades\Config;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class EasyPracticeConnector extends Connector
{
    use AcceptsJson;

    public function resolveBaseUrl(): string
    {
        return 'https://system.easypractice.net/api/v1';
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator(Config::get('services.easy-practice.token'));
    }
}

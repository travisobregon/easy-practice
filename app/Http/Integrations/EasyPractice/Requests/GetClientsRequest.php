<?php

namespace App\Http\Integrations\EasyPractice\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetClientsRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/clients';
    }
}

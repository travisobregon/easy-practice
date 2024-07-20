<?php

namespace App\Http\Integrations\EasyPractice\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetBookingsRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/bookings';
    }
}

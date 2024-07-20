<?php

namespace App\Http\Integrations\EasyPractice\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateBookingRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $payload,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/bookings';
    }

    protected function defaultBody(): array
    {
        return array_merge(
            ['type' => 'client_booking', 'calendar_id' => 625814],
            $this->payload
        );
    }
}

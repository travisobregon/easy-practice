<?php

namespace App\Http\Integrations\EasyPractice\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateBookingRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(protected readonly string $id, protected array $payload) {}

    public function resolveEndpoint(): string
    {
        return "/bookings/{$this->id}";
    }

    protected function defaultBody(): array
    {
        return $this->payload;
    }
}

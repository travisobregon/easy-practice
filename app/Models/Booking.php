<?php

namespace App\Models;

use App\Http\Integrations\EasyPractice\EasyPracticeConnector;
use App\Http\Integrations\EasyPractice\Requests\GetBookingsRequest;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Sushi\Sushi;

class Booking extends Model implements Eventable
{
    use HasFactory;
    use Sushi;

    protected $with = ['client'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getRows(): array
    {
        $easyPractice = new EasyPracticeConnector;
        $request = new GetBookingsRequest;

        return $easyPractice->send($request)->collect('data')->map(function ($booking) {
            return Arr::only($booking, [
                'id',
                'client_id',
                'calendar_id',
                'start',
                'end',
                'status',
                'created_at',
                'updated_at',
            ]);
        })->toArray();
    }

    public function toEvent(): array|Event
    {
        $backgroundColors = [
            '#4E91E5', // Soft Blue
            '#FF7E79', // Coral Pink
            '#7AE7B9', // Mint Green
            '#B18EF2', // Lavender
            '#FFD166', // Sunshine Yellow
        ];

        return Event::make($this)
            ->title($this->client->name)
            ->backgroundColor($backgroundColors[$this->client->id % count($backgroundColors)])
            ->start($this->start)
            ->end($this->end);
    }
}

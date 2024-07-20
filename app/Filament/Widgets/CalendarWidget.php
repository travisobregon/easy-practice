<?php

namespace App\Filament\Widgets;

use App\Http\Integrations\EasyPractice\EasyPracticeConnector;
use App\Http\Integrations\EasyPractice\Requests\GetBookingsRequest;
use App\Http\Integrations\EasyPractice\Requests\UpdateBookingRequest;
use App\Models\Booking;
use Closure;
use Filament\Actions\Action;
use Filament\Forms;
use Guava\Calendar\Actions\EditAction;
use Guava\Calendar\Widgets\CalendarWidget as BaseCalendarWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class CalendarWidget extends BaseCalendarWidget
{
    protected string|Closure|HtmlString|null $heading = '';

    protected bool $eventClickEnabled = true;

    protected ?string $defaultEventClickAction = 'edit';

    public function getEvents(array $fetchInfo = []): Collection|array
    {
        return Booking::query()
            ->when(filled($fetchInfo['start']), fn ($query) => $query->where('start', '>=', $fetchInfo['start']))
            ->when(filled($fetchInfo['end']), fn ($query) => $query->where('end', '<=', $fetchInfo['end']))
            ->get();
    }

    public function getSchema(?string $model = null): ?array
    {
        return [
            Forms\Components\DateTimePicker::make('start')
                ->required()
                ->format('Y-m-d\TH:i:sP'),
            Forms\Components\DateTimePicker::make('end')
                ->required()
                ->after('start')
                ->format('Y-m-d\TH:i:sP'),
        ];
    }

    public function editAction(): Action
    {
        return EditAction::make()
            ->using(function (Model $record, array $data) {
                $easyPractice = new EasyPracticeConnector;
                $request = new UpdateBookingRequest($record->id, $data);

                $easyPractice->send($request);

                $easyPractice->invalidateCache();
                $easyPractice->send(new GetBookingsRequest);
            });
    }

    public function authorize($ability, $arguments = []): bool
    {
        return true;
    }
}

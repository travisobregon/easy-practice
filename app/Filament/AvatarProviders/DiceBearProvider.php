<?php

namespace App\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class DiceBearProvider implements AvatarProvider
{
    public function get(Model | Authenticatable $record): string
    {
        return URL::query('https://api.dicebear.com/9.x/bottts-neutral/svg', [
            'seed' => Filament::getNameForDefaultAvatar($record),
        ]);
    }
}

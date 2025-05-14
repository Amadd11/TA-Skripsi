<?php

namespace App\Filament\Resources\PemberianObatResource\Pages;

use App\Filament\Resources\PemberianObatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPemberianObat extends EditRecord
{
    protected static string $resource = PemberianObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

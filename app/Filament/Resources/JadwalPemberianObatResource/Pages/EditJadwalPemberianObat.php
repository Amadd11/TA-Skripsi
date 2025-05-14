<?php

namespace App\Filament\Resources\JadwalPemberianObatResource\Pages;

use App\Filament\Resources\JadwalPemberianObatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalPemberianObat extends EditRecord
{
    protected static string $resource = JadwalPemberianObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

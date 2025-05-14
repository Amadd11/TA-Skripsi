<?php

namespace App\Filament\Resources\JadwalPemberianObatResource\Pages;

use App\Filament\Resources\JadwalPemberianObatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalPemberianObats extends ListRecords
{
    protected static string $resource = JadwalPemberianObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

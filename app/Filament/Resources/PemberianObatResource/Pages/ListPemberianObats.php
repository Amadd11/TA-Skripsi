<?php

namespace App\Filament\Resources\PemberianObatResource\Pages;

use App\Filament\Resources\PemberianObatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPemberianObats extends ListRecords
{
    protected static string $resource = PemberianObatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

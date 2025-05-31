<?php

namespace App\Filament\Resources\PemberianObatResource\Pages;

use Filament\Actions;
use App\Models\JadwalPemberianObat;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PemberianObatResource;
use App\Filament\Resources\JadwalPemberianObatResource;

class CreatePemberianObat extends CreateRecord
{
    protected static string $resource = PemberianObatResource::class;

    protected function afterCreate(): void
    {
        if ($this->record->jadwal_pemberian_obat_id) {
            JadwalPemberianObat::where('id', $this->record->jadwal_pemberian_obat_id)
                ->update([
                    'status' => 'diberikan',
                    'pemberian_obat_id' => $this->record->id,
                ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return JadwalPemberianObatResource::getUrl(); // kembali ke index
    }
}

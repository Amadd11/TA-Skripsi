<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\JadwalPemberianObat;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter; // Import SelectFilter
use Filament\Widgets\TableWidget as BaseWidget;

// class Schedule extends BaseWidget
// {
//     protected static ?int $sort = 3;

//     protected int | string | array $columnSpan = 'full';

//     public function table(Table $table): Table
//     {
//         return $table
//             ->query(
//                 JadwalPemberianObat::with(['pasien', 'ruangan']) // Eager loading relasi
//                     ->orderByDesc('waktu') // Mengurutkan berdasarkan waktu
//             )
//             ->columns([
//                 TextColumn::make('pasien.nama') // Nama pasien
//                     ->label('Pasien')
//                     ->searchable()
//                     ->sortable(),

//                 TextColumn::make('perawat.name') // Nama perawat
//                     ->label('Perawat')
//                     ->searchable()
//                     ->sortable(),

//                 TextColumn::make('ruangan.nama_ruangan') // Nama ruangan
//                     ->label('Ruangan')
//                     ->searchable()
//                     ->sortable(),

//                 TextColumn::make('waktu') // Waktu pemberian obat
//                     ->label('Waktu Pemberian')
//                     ->dateTime('d/m/Y H:i') // Format tanggal dan waktu
//                     ->sortable(),

//                 TextColumn::make('status') // Status pemberian obat
//                     ->label('Status')
//                     ->badge() // Menggunakan badge
//                     ->color(fn(string $state): string => match ($state) {
//                         'waiting' => 'warning',  // Kuning untuk status 'waiting'
//                         'diberikan' => 'info',   // Biru untuk status 'diberikan'
//                         'canceled' => 'danger',  // Merah untuk status 'canceled'
//                         default => 'secondary',  // Abu-abu untuk status lainnya
//                     })
//                     ->sortable(),
//             ]) // Perbaiki titik koma ke koma
//             ->filters([  // Menambahkan filter untuk status
//                 SelectFilter::make('status')
//                     ->options([
//                         'waiting' => 'Waiting',
//                         'diberikan' => 'Diberikan',
//                         'canceled' => 'Canceled',
//                     ]) // Menambahkan query untuk memfilter status
//             ]);
//     }
// }

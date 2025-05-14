<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use App\Models\JadwalPemberianObat;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\JadwalPemberianObatResource\Pages;
use App\Filament\Resources\JadwalPemberianObatResource\RelationManagers;

class JadwalPemberianObatResource extends Resource
{
    protected static ?string $model = JadwalPemberianObat::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->lazy()
                    ->maxLength(255),
                Forms\Components\Select::make('obat_id')
                    ->relationship('obats', 'nama')
                    ->required()
                    ->multiple()
                    ->label('Obat')  // Label untuk form input
                    ->searchable()  // Opsional: memungkinkan pencarian dalam daftar obat
                    ->preload(),  //
                Forms\Components\Select::make('user_id')
                    ->label('Perawat')
                    ->relationship(name: 'perawat', titleAttribute: 'name') // Ambil nama pasien
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('pasien_id')
                    ->label('Pasien')
                    ->relationship(name: 'pasien', titleAttribute: 'nama') // Ambil nama pasien
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('ruangan_id')
                    ->label('Ruangan')
                    ->relationship(name: 'ruangan', titleAttribute: 'nama_ruangan') // Ambil nama pasien
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->disabled()
                    ->maxLength(255),
                Forms\Components\TextInput::make('dosis')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('rute')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('interval')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('keterangan')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('waktu')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pasien.nama') // Ambil nama dari relasi
                    ->label('Nama Pasien')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('obats.nama') // Ambil nama dari relasi
                    ->label('Nama Obat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('perawat.name') // Ambil nama dari relasi
                    ->label('Nama Obat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dosis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rute')
                    ->searchable(),
                Tables\Columns\TextColumn::make('interval')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ruangan.nama_ruangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'waiting' => 'warning',
                        'diberikan' => 'info',
                        'canceled' => 'danger',
                        default => 'secondary',
                    }),
            ])
            ->filters([
                //
                SelectFilter::make('status')
                    ->options([
                        'waiting' => 'Waiting',
                        'diberikan' => 'Diberikan',
                        'canceled' => 'Canceled',
                    ])
            ])
            ->actions([
                Tables\Actions\Action::make('diberikan')
                    ->label('Tandai Diberikan')
                    ->button() // Menggunakan badge alih-alih tombol
                    ->color('success') // Hijau untuk 'diberikan'
                    ->requiresConfirmation() // Memerlukan konfirmasi sebelum aksi
                    ->action(function (JadwalPemberianObat $jadwalpemberianobat) {
                        // Mengubah status jadwal menjadi 'diberikan'
                        $jadwalpemberianobat->update([
                            'status' => 'diberikan',
                        ]);

                        // Menampilkan notifikasi
                        Notification::make()
                            ->success()
                            ->title('Obat Diberikan')
                            ->body('Status jadwal berhasil diperbarui ke "diberikan".')
                            ->icon('heroicon-o-check')
                            ->send();
                    })
                    ->hidden(fn(JadwalPemberianObat $jadwalpemberianobat) => $jadwalpemberianobat->status !== 'waiting'), // Menyembunyikan aksi jika status bukan 'waiting'

                Tables\Actions\Action::make('canceled')
                    ->label('Tandai Dibatalkan')
                    ->button() // Menggunakan badge alih-alih tombol
                    ->color('danger') // Merah untuk 'canceled'
                    ->requiresConfirmation() // Memerlukan konfirmasi sebelum aksi
                    ->action(function (JadwalPemberianObat $jadwalpemberianobat) {
                        // Mengubah status jadwal menjadi 'canceled'
                        $jadwalpemberianobat->update([
                            'status' => 'canceled',
                        ]);

                        // Menampilkan notifikasi
                        Notification::make()
                            ->success()
                            ->title('Obat Dibatalkan')
                            ->body('Status jadwal berhasil diperbarui ke "canceled".')
                            ->icon('heroicon-o-x-mark')
                            ->send();
                    })
                    ->hidden(fn(JadwalPemberianObat $jadwalpemberianobat) => $jadwalpemberianobat->status !== 'waiting'), // Menyembunyikan aksi jika status bukan 'waiting'


                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalPemberianObats::route('/'),
            'create' => Pages\CreateJadwalPemberianObat::route('/create'),
            'edit' => Pages\EditJadwalPemberianObat::route('/{record}/edit'),
        ];
    }
}

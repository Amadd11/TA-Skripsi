<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PemberianObat;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use App\Models\JadwalPemberianObat;
use Filament\Support\Enums\FontWeight;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PemberianObatResource\Pages;
use App\Filament\Resources\PemberianObatResource\RelationManagers;

class PemberianObatResource extends Resource
{
    protected static ?string $model = PemberianObat::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationLabel = 'Pemberian Obat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pasien_id')
                    ->label('Pasien')
                    ->relationship('pasien', titleAttribute: 'nama') // Ambil nama pasien
                    ->searchable()
                    ->preload()
                    ->default(function () {
                        $jadwalId = request()->get('jadwal_id');
                        if ($jadwalId) {
                            return JadwalPemberianObat::find($jadwalId)?->pasien_id;
                        }
                        return null;
                    })
                    ->disabled(),
                Forms\Components\Select::make('user_id')
                    ->label('Perawat')
                    ->relationship('perawat', titleAttribute: 'name') // Ambil nama pasien
                    ->searchable()
                    ->preload()
                    ->default(auth()->user()->id)
                    ->disabled(),
                Forms\Components\Hidden::make('jadwal_pemberian_obat_id')
                    ->default(fn() => request()->get('jadwal_id'))
                    ->required(),
                Forms\Components\Toggle::make('centang_semua')
                    ->label('Choose All')
                    ->live() // Agar langsung bereaksi saat diubah
                    ->afterStateUpdated(function (callable $set, $state) {
                        $fields = [
                            'benar_pasien',
                            'benar_obat',
                            'benar_dosis',
                            'benar_cara',
                            'benar_waktu',
                            'benar_dokumentasi',
                            'benar_alasan',
                            'benar_respon',
                            'benar_edukasi',
                            'benar_evaluasi',
                            'benar_bentuk',
                            'benar_penyimpanan',
                        ];

                        // Loop untuk mengatur setiap toggle sesuai dengan state "centang_semua"
                        foreach ($fields as $field) {
                            $set($field, $state);
                        }
                    }),

                Forms\Components\Toggle::make('benar_pasien')
                    ->required(),
                Forms\Components\Toggle::make('benar_obat')
                    ->required(),
                Forms\Components\Toggle::make('benar_dosis')
                    ->required(),
                Forms\Components\Toggle::make('benar_cara')
                    ->required(),
                Forms\Components\Toggle::make('benar_waktu')
                    ->required(),
                Forms\Components\Toggle::make('benar_dokumentasi')
                    ->required(),
                Forms\Components\Toggle::make('benar_alasan')
                    ->required(),
                Forms\Components\Toggle::make('benar_respon')
                    ->required(),
                Forms\Components\Toggle::make('benar_edukasi')
                    ->required(),
                Forms\Components\Toggle::make('benar_evaluasi')
                    ->required(),
                Forms\Components\Toggle::make('benar_bentuk')
                    ->required(),
                Forms\Components\Toggle::make('benar_penyimpanan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pasien.nama')
                    ->label('Nama Pasien')
                    ->searchable(),
                Tables\Columns\TextColumn::make('perawat.name') // Ambil nama dari relasi
                    ->label('Nama Perawat')
                    ->searchable()
                    ->sortable(),
                // Checklist boolean lainnya
                Tables\Columns\IconColumn::make('benar_pasien')->boolean(),
                Tables\Columns\IconColumn::make('benar_obat')->boolean(),
                Tables\Columns\IconColumn::make('benar_dosis')->boolean(),
                Tables\Columns\IconColumn::make('benar_cara')->boolean(),
                Tables\Columns\IconColumn::make('benar_waktu')->boolean(),
                Tables\Columns\IconColumn::make('benar_dokumentasi')->boolean(),
                Tables\Columns\IconColumn::make('benar_alasan')->boolean(),
                Tables\Columns\IconColumn::make('benar_respon')->boolean(),
                Tables\Columns\IconColumn::make('benar_edukasi')->boolean(),
                Tables\Columns\IconColumn::make('benar_evaluasi')->boolean(),
                Tables\Columns\IconColumn::make('benar_bentuk')->boolean(),
                Tables\Columns\IconColumn::make('benar_penyimpanan')->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn(PemberianObat $record) => match (self::getObatColor($record)) {
                        'success' => 'heroicon-o-shield-check',  // Ikon hijau untuk Lengkap
                        'warning' => 'heroicon-o-exclamation-circle',  // Ikon kuning untuk Setengah
                        'danger' => 'heroicon-o-x-mark',  // Ikon merah untuk Kurang
                        default => 'heroicon-o-question-mark-circle',  // Ikon fallback
                    })
                    ->color(fn(PemberianObat $record) => self::getObatColor($record))  // Memberikan warna sesuai status
                    ->tooltip(fn(PemberianObat $record) => match (self::getObatColor($record)) {
                        'success' => 'Lengkap',
                        'warning' => 'Setengah',
                        'danger' => 'Kurang',
                        default => 'Status tidak diketahui',
                    })
                    ->getStateUsing(function (PemberianObat $record) {
                        $totalChecklist = 12;
                        $checkedCount = collect([
                            $record->benar_pasien,
                            $record->benar_obat,
                            $record->benar_dosis,
                            $record->benar_cara,
                            $record->benar_waktu,
                            $record->benar_dokumentasi,
                            $record->benar_alasan,
                            $record->benar_respon,
                            $record->benar_edukasi,
                            $record->benar_evaluasi,
                            $record->benar_bentuk,
                            $record->benar_penyimpanan,
                        ])->filter()->count();

                        return match (true) {
                            $checkedCount === $totalChecklist => 'Lengkap',
                            $checkedCount >= ($totalChecklist / 2) => 'Setengah',
                            default => 'Kurang',
                        };
                    })
                    ->sortable(),



            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Filament::auth()->user();

        if ($user && $user->hasRole('perawat')) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    /**
     * Fungsi untuk menentukan warna berdasarkan jumlah checklist aktif
     */
    public static function getObatColor(PemberianObat $obat)
    {
        // Hitung jumlah checklist aktif
        $totalChecklist = 12; // Total checklist yang ada
        $checkedCount = collect([
            $obat->benar_pasien,
            $obat->benar_obat,
            $obat->benar_dosis,
            $obat->benar_cara,
            $obat->benar_waktu,
            $obat->benar_dokumentasi,
            $obat->benar_alasan,
            $obat->benar_respon,
            $obat->benar_edukasi,
            $obat->benar_evaluasi,
            $obat->benar_bentuk,
            $obat->benar_penyimpanan,
        ])->filter()->count();

        // Tentukan warna berdasarkan jumlah checklist aktif
        return match (true) {
            $checkedCount === $totalChecklist => 'success', // Semua dicentang -> Hijau
            $checkedCount >= ($totalChecklist / 2) => 'warning', // Setengah atau lebih -> Kuning
            default => 'danger', // Kurang dari setengah -> Merah
        };
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
            'index' => Pages\ListPemberianObats::route('/'),
            'create' => Pages\CreatePemberianObat::route('/create'),
            'edit' => Pages\EditPemberianObat::route('/{record}/edit'),
        ];
    }
}

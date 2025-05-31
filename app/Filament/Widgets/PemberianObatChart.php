<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\PemberianObat;
use Filament\Widgets\ChartWidget;

class PemberianObatChart extends ChartWidget
{
    protected static ?string $heading = 'Pemberian Obat Berdasarkan Prinsip 12 Benar';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        $totalChecklist = 12;

        // Inisialisasi array 1-31
        $labels = range(1, 31);
        $green = array_fill(0, 31, 0);
        $yellow = array_fill(0, 31, 0);
        $red = array_fill(0, 31, 0);

        PemberianObat::whereBetween('created_at', [$start, $end])->get()->each(function ($record) use (&$green, &$yellow, &$red, $totalChecklist) {
            $count = collect([
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

            $day = Carbon::parse($record->created_at)->day - 1;

            if ($count === $totalChecklist) {
                $green[$day]++;
            } elseif ($count >= ($totalChecklist / 2)) {
                $yellow[$day]++;
            } else {
                $red[$day]++;
            }
        });
        return [
            'datasets' => [
                [
                    'label' => 'Lengkap (Hijau)',
                    'data' => $green,
                    'backgroundColor' => 'rgb(34 197 94)', // green-500
                    'borderWidth' => 0,
                ],
                [
                    'label' => 'Setengah (Kuning)',
                    'data' => $yellow,
                    'backgroundColor' => 'rgb(234 179 8)', // yellow-500
                    'borderWidth' => 0,
                ],
                [
                    'label' => 'Kurang (Merah)',
                    'data' => $red,
                    'backgroundColor' => 'rgb(239 68 68)', // red-500
                    'borderWidth' => 0,
                ],
            ],
            'labels' => array_map(fn($day) => str_pad($day, 2, '0', STR_PAD_LEFT), $labels),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

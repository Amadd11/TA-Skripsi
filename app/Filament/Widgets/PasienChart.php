<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Pasien;
use Carbon\Carbon;

class PasienChart extends ChartWidget
{
    protected static ?string $heading = 'Chart Pasien Berdasarkan Bulan';

    protected static ?int $sort = 2;

    protected function getData(): array
    {

        $data = Pasien::whereYear('created_at', Carbon::now()->year)
            ->selectRaw('MONTH(created_at) as month, count(*) as total')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('total', 'month');

        $labels = [];
        $values = [];

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::create()->month($month)->format('F');
            $values[] = $data->get($month, 0);
        }

        return [
            'labels' => $labels, // Label untuk setiap bulan
            'datasets' => [
                [
                    'label' => 'Jumlah Pasien',
                    'data' => $values, // Data pasien berdasarkan bulan
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Jenis chart: bisa diganti dengan 'line', 'bubble', dll.
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\PemesananTiket;
use Filament\Widgets\ChartWidget;

class StatusPemesananChart extends ChartWidget
{
    protected ?string $heading = 'Status Pemesanan Tiket';

    protected ?string $description = 'Perbandingan status pemesanan tiket berdasarkan data yang masuk.';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->user()?->canViewReportData() ?? false;
    }

    protected function getData(): array
    {
        $pending = PemesananTiket::where('status_pemesanan', 'pending')->count();
        $diterima = PemesananTiket::where('status_pemesanan', 'diterima')->count();
        $ditolak = PemesananTiket::where('status_pemesanan', 'ditolak')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pemesanan',
                    'data' => [
                        $pending,
                        $diterima,
                        $ditolak,
                    ],
                ],
            ],
            'labels' => [
                'Pending',
                'Diterima',
                'Ditolak',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
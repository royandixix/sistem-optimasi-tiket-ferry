<?php

namespace App\Filament\Widgets;

use App\Models\HasilOptimasi;
use Filament\Widgets\ChartWidget;

class LoadFactorChart extends ChartWidget
{
    protected ?string $heading = 'Perbandingan Load Factor';

    protected ?string $description = 'Perbandingan tingkat pemanfaatan kapasitas kapal antara FCFS dan Greedy Heuristik.';

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        return auth()->user()?->canViewReportData() ?? false;
    }

    protected function getData(): array
    {
        $fcfs = HasilOptimasi::where('metode', 'fcfs')->avg('load_factor') ?? 0;
        $greedy = HasilOptimasi::where('metode', 'greedy')->avg('load_factor') ?? 0;

        return [
            'datasets' => [
                [
                    'label' => 'Load Factor (%)',
                    'data' => [
                        round($fcfs, 2),
                        round($greedy, 2),
                    ],
                ],
            ],
            'labels' => [
                'FCFS',
                'Greedy Heuristik',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
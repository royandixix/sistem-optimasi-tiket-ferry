<?php

namespace App\Filament\Widgets;

use App\Models\HasilOptimasi;
use App\Models\JadwalKeberangkatan;
use App\Models\Kapal;
use App\Models\PemesananTiket;
use App\Models\Penumpang;
use App\Models\Rute;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatistikDashboardWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Ringkasan Sistem';

    protected ?string $description = 'Monitoring data utama sistem optimasi alokasi tiket kapal ferry.';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'sm' => 'full',
        'md' => 'full',
        'lg' => 'full',
        'xl' => 'full',
        '2xl' => 'full',
    ];

    public static function canView(): bool
    {
        return auth()->user()?->canViewReportData() ?? false;
    }

    protected function getStats(): array
    {
        $totalKapal = Kapal::count();
        $totalRute = Rute::count();
        $jadwalTersedia = JadwalKeberangkatan::where('status', 'tersedia')->count();
        $totalPenumpang = Penumpang::count();
        $pemesananPending = PemesananTiket::where('status_pemesanan', 'pending')->count();
        $pemesananDiterima = PemesananTiket::where('status_pemesanan', 'diterima')->count();
        $pemesananDitolak = PemesananTiket::where('status_pemesanan', 'ditolak')->count();
        $rataLoadFactor = HasilOptimasi::avg('load_factor') ?? 0;

        return [
            Stat::make('Total Kapal', number_format($totalKapal))
                ->description('Armada ferry terdaftar')
                ->descriptionIcon(Heroicon::OutlinedRocketLaunch)
                ->chart([1, 2, 3, 4, 5, $totalKapal])
                ->color('info'),

            Stat::make('Total Rute', number_format($totalRute))
                ->description('Rute penyeberangan')
                ->descriptionIcon(Heroicon::OutlinedMapPin)
                ->chart([1, 2, 2, 3, 4, $totalRute])
                ->color('gray'),

            Stat::make('Jadwal Tersedia', number_format($jadwalTersedia))
                ->description('Siap digunakan untuk pemesanan')
                ->descriptionIcon(Heroicon::OutlinedCalendarDays)
                ->chart([1, 2, 3, 4, 5, $jadwalTersedia])
                ->color('success'),

            Stat::make('Total Penumpang', number_format($totalPenumpang))
                ->description('Data penumpang terdaftar')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->chart([1, 3, 5, 7, 9, $totalPenumpang])
                ->color('warning'),

            Stat::make('Pemesanan Pending', number_format($pemesananPending))
                ->description('Menunggu proses alokasi')
                ->descriptionIcon(Heroicon::OutlinedTicket)
                ->chart([$pemesananDitolak, $pemesananDiterima, $pemesananPending])
                ->color($pemesananPending > 0 ? 'danger' : 'success'),

            Stat::make('Rata-rata Load Factor', number_format($rataLoadFactor, 2) . '%')
                ->description('Pemanfaatan kapasitas kapal')
                ->descriptionIcon(Heroicon::OutlinedChartBar)
                ->chart([30, 45, 55, 65, 75, round($rataLoadFactor, 2)])
                ->color($rataLoadFactor >= 80 ? 'success' : 'info'),
        ];
    }
}

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
        $rataLoadFactor = HasilOptimasi::avg('load_factor') ?? 0;

        return [
            Stat::make('Total Kapal', $totalKapal)
                ->description('Armada ferry yang terdaftar')
                ->descriptionIcon(Heroicon::OutlinedRocketLaunch)
                ->color('info'),

            Stat::make('Total Rute', $totalRute)
                ->description('Rute penyeberangan aktif/nonaktif')
                ->descriptionIcon(Heroicon::OutlinedMapPin)
                ->color('gray'),

            Stat::make('Jadwal Tersedia', $jadwalTersedia)
                ->description('Jadwal yang masih dapat digunakan')
                ->descriptionIcon(Heroicon::OutlinedCalendarDays)
                ->color('success'),

            Stat::make('Total Penumpang', $totalPenumpang)
                ->description('Data penumpang terdaftar')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('warning'),

            Stat::make('Pemesanan Pending', $pemesananPending)
                ->description('Menunggu proses alokasi tiket')
                ->descriptionIcon(Heroicon::OutlinedTicket)
                ->color($pemesananPending > 0 ? 'danger' : 'success'),

            Stat::make('Rata-rata Load Factor', number_format($rataLoadFactor, 2) . '%')
                ->description('Rata-rata pemanfaatan kapasitas kapal')
                ->descriptionIcon(Heroicon::OutlinedChartBar)
                ->color('success'),
        ];
    }
}
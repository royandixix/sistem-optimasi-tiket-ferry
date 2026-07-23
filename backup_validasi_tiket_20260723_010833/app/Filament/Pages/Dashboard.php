<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! $user->isPetugas();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function getTitle(): string
    {
        return 'Dashboard Optimasi Tiket Ferry';
    }

    public function getHeading(): string
    {
        return 'Dashboard Optimasi Tiket Ferry';
    }

    public function getSubheading(): ?string
    {
        return 'Monitoring data kapal, rute, jadwal, pemesanan tiket, alokasi tiket, dan hasil optimasi kapasitas kapal ferry.';
    }

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'sm' => 1,
            'md' => 1,
            'lg' => 1,
            'xl' => 2,
            '2xl' => 2,
        ];
    }
}
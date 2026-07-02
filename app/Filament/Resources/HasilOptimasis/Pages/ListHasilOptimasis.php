<?php

namespace App\Filament\Resources\HasilOptimasis\Pages;

use App\Filament\Resources\HasilOptimasis\HasilOptimasiResource;
use App\Models\JadwalKeberangkatan;
use App\Services\TiketAllocationService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListHasilOptimasis extends ListRecords
{
    protected static string $resource = HasilOptimasiResource::class;

    protected static ?string $title = 'Laporan Hasil Optimasi';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('proses_fcfs')
                ->label('Proses FCFS')
                ->color('gray')
                ->form([
                    Select::make('jadwal_id')
                        ->label('Jadwal Keberangkatan')
                        ->options(fn (): array => $this->getJadwalOptions())
                        ->searchable()
                        ->required(),
                ])
                ->requiresConfirmation()
                ->modalHeading('Proses Optimasi FCFS')
                ->modalDescription('Sistem akan memproses alokasi tiket berdasarkan urutan waktu pemesanan paling awal.')
                ->modalSubmitActionLabel('Proses Sekarang')
                ->visible(fn (): bool => static::getResource()::canCreate())
                ->action(function (array $data): void {
                    $hasil = app(TiketAllocationService::class)->process((int) $data['jadwal_id'], 'fcfs');

                    Notification::make()
                        ->title('Proses FCFS berhasil')
                        ->body('Load factor: ' . $hasil->load_factor . '%. Tiket diterima: ' . $hasil->total_tiket_diterima . '. Tiket ditolak: ' . $hasil->total_tiket_ditolak . '.')
                        ->success()
                        ->send();
                }),

            Action::make('proses_greedy')
                ->label('Proses Greedy')
                ->color('success')
                ->form([
                    Select::make('jadwal_id')
                        ->label('Jadwal Keberangkatan')
                        ->options(fn (): array => $this->getJadwalOptions())
                        ->searchable()
                        ->required(),
                ])
                ->requiresConfirmation()
                ->modalHeading('Proses Optimasi Greedy Heuristik')
                ->modalDescription('Sistem akan memproses alokasi tiket berdasarkan prioritas jumlah tiket terbesar yang masih dapat masuk ke kapasitas kapal.')
                ->modalSubmitActionLabel('Proses Sekarang')
                ->visible(fn (): bool => static::getResource()::canCreate())
                ->action(function (array $data): void {
                    $hasil = app(TiketAllocationService::class)->process((int) $data['jadwal_id'], 'greedy');

                    Notification::make()
                        ->title('Proses Greedy berhasil')
                        ->body('Load factor: ' . $hasil->load_factor . '%. Tiket diterima: ' . $hasil->total_tiket_diterima . '. Tiket ditolak: ' . $hasil->total_tiket_ditolak . '.')
                        ->success()
                        ->send();
                }),

            CreateAction::make()
                ->label('Tambah Hasil Optimasi')
                ->visible(fn (): bool => static::getResource()::canCreate()),
        ];
    }

    private function getJadwalOptions(): array
    {
        return JadwalKeberangkatan::query()
            ->with(['kapal', 'rute'])
            ->orderByDesc('tanggal_berangkat')
            ->orderByDesc('jam_berangkat')
            ->get()
            ->mapWithKeys(function (JadwalKeberangkatan $jadwal): array {
                $tanggal = optional($jadwal->tanggal_berangkat)->format('d-m-Y') ?? '-';
                $jam = $jadwal->jam_berangkat ? substr((string) $jadwal->jam_berangkat, 0, 5) : '-';
                $kapal = $jadwal->kapal?->nama_kapal ?? '-';
                $asal = $jadwal->rute?->pelabuhan_asal ?? '-';
                $tujuan = $jadwal->rute?->pelabuhan_tujuan ?? '-';
                $sisa = $jadwal->sisa_kapasitas ?? 0;

                return [
                    $jadwal->id => "{$tanggal} {$jam} - {$kapal} ({$asal} ke {$tujuan}) | Sisa {$sisa} kursi",
                ];
            })
            ->toArray();
    }
}

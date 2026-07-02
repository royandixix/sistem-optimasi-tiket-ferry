<?php

namespace App\Filament\Resources\JadwalKeberangkatans\Pages;

use App\Filament\Resources\JadwalKeberangkatans\JadwalKeberangkatanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJadwalKeberangkatan extends EditRecord
{
    protected static string $resource = JadwalKeberangkatanResource::class;

    protected static ?string $title = 'Ubah Jadwal Keberangkatan';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Jadwal')
                ->modalHeading('Hapus Jadwal Keberangkatan')
                ->modalDescription('Data jadwal yang dihapus tidak dapat dikembalikan.')
                ->modalSubmitActionLabel('Ya, Hapus'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $kapasitasTotal = (int) ($data['kapasitas_total'] ?? 0);
        $kapasitasTerpakai = (int) ($data['kapasitas_terpakai'] ?? 0);

        $data['sisa_kapasitas'] = max($kapasitasTotal - $kapasitasTerpakai, 0);

        if ($data['sisa_kapasitas'] <= 0 && $kapasitasTotal > 0) {
            $data['status'] = 'penuh';
        }

        return $data;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Jadwal keberangkatan berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
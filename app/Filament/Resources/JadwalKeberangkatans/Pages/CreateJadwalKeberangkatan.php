<?php

namespace App\Filament\Resources\JadwalKeberangkatans\Pages;

use App\Filament\Resources\JadwalKeberangkatans\JadwalKeberangkatanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalKeberangkatan extends CreateRecord
{
    protected static string $resource = JadwalKeberangkatanResource::class;

    protected static ?string $title = 'Tambah Jadwal Keberangkatan';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $kapasitasTotal = (int) ($data['kapasitas_total'] ?? 0);
        $kapasitasTerpakai = (int) ($data['kapasitas_terpakai'] ?? 0);

        $data['sisa_kapasitas'] = max($kapasitasTotal - $kapasitasTerpakai, 0);

        if ($data['sisa_kapasitas'] <= 0 && $kapasitasTotal > 0) {
            $data['status'] = 'penuh';
        }

        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Jadwal keberangkatan berhasil ditambahkan';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
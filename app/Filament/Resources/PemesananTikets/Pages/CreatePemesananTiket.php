<?php

namespace App\Filament\Resources\PemesananTikets\Pages;

use App\Filament\Resources\PemesananTikets\PemesananTiketResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
class CreatePemesananTiket extends CreateRecord
{
    protected static string $resource = PemesananTiketResource::class;

    protected static ?string $title = 'Tambah Pemesanan Tiket';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['kode_pemesanan'] = $data['kode_pemesanan']
            ?? 'PM-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

        $data['waktu_pemesanan'] = $data['waktu_pemesanan'] ?? now();
        $data['created_by'] = $data['created_by'] ?? auth()->id();
        $data['status_pemesanan'] = $data['status_pemesanan'] ?? 'pending';

        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Pemesanan tiket berhasil ditambahkan';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
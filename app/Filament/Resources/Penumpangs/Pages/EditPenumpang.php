<?php

namespace App\Filament\Resources\Penumpangs\Pages;

use App\Filament\Resources\Penumpangs\PenumpangResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPenumpang extends EditRecord
{
    protected static string $resource = PenumpangResource::class;

    protected static ?string $title = 'Ubah Data Penumpang';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Penumpang')
                ->modalHeading('Hapus Data Penumpang')
                ->modalDescription('Data penumpang yang dihapus tidak dapat dikembalikan. Pastikan data ini tidak sedang digunakan pada pemesanan tiket.')
                ->modalSubmitActionLabel('Ya, Hapus'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data penumpang berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
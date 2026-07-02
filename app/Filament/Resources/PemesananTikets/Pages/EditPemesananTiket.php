<?php

namespace App\Filament\Resources\PemesananTikets\Pages;

use App\Filament\Resources\PemesananTikets\PemesananTiketResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
class EditPemesananTiket extends EditRecord
{
    protected static string $resource = PemesananTiketResource::class;

    protected static ?string $title = 'Ubah Pemesanan Tiket';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Pemesanan')
                ->modalHeading('Hapus Pemesanan Tiket')
                ->modalDescription('Data pemesanan tiket yang dihapus tidak dapat dikembalikan.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->visible(fn (): bool => auth()->user()?->isSuperAdmin()),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['created_by'] = $data['created_by'] ?? auth()->id();

        return $data;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Pemesanan tiket berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
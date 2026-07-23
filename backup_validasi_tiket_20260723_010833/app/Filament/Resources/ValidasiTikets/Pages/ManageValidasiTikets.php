<?php

namespace App\Filament\Resources\ValidasiTikets\Pages;

use App\Filament\Resources\ValidasiTikets\ValidasiTiketResource;
use App\Services\TicketValidationService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Marcelorodrigo\FilamentBarcodeScannerField\Forms\Components\BarcodeInput;

class ManageValidasiTikets extends ManageRecords
{
    protected static string $resource = ValidasiTiketResource::class;

    protected static ?string $title = 'Validasi Tiket Penumpang';

    public function getHeading(): string
    {
        return 'Validasi Tiket Penumpang';
    }

    public function getSubheading(): ?string
    {
        return 'Scan QR Code atau masukkan kode booking untuk memvalidasi tiket penumpang.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('validasi_tiket')
                ->label('Validasi Tiket')
                ->icon(Heroicon::OutlinedQrCode)
                ->color('success')
                ->modalHeading('Validasi Tiket Penumpang')
                ->modalDescription(
                    'Tekan ikon QR Code untuk membuka kamera atau masukkan kode booking secara manual.'
                )
                ->modalSubmitActionLabel('Validasi Sekarang')
                ->modalCancelActionLabel('Batal')
                ->schema([
                    BarcodeInput::make('kode_tiket')
                        ->label('QR Code atau Kode Booking')
                        ->icon('heroicon-o-qr-code')
                        ->placeholder('Scan QR Code atau masukkan kode booking PM-...')
                        ->helperText(
                            'Hasil pemindaian QR atau kode booking akan diperiksa langsung pada data pemesanan.'
                        )
                        ->required()
                        ->maxLength(100)
                        ->autofocus(),
                ])
                ->visible(
                    fn (): bool => Auth::user()?->canValidateTickets() ?? false
                )
                ->action(function (array $data): void {
                    $userId = Auth::id();

                    if ($userId === null) {
                        Notification::make()
                            ->title('Sesi login tidak ditemukan')
                            ->body('Silakan login kembali sebelum melakukan validasi tiket.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $result = app(TicketValidationService::class)->validate(
                        input: (string) ($data['kode_tiket'] ?? ''),
                        validatedBy: $userId,
                    );

                    $this->resetTable();

                    $notification = Notification::make()
                        ->title($result['title'])
                        ->body($result['message'])
                        ->persistent();

                    if ($result['success']) {
                        $notification->success()->send();

                        return;
                    }

                    $notification->danger()->send();
                }),
        ];
    }
}
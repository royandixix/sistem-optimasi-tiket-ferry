<?php

namespace App\Filament\Resources\AlokasiTikets\Pages;

use App\Filament\Resources\AlokasiTikets\AlokasiTiketResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAlokasiTikets extends ListRecords
{
    protected static string $resource = AlokasiTiketResource::class;

    protected static ?string $title = 'Data Alokasi Tiket';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Alokasi Tiket')
                ->visible(fn (): bool => static::getResource()::canCreate()),
        ];
    }
}
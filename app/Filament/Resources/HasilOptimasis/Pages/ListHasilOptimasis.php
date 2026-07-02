<?php

namespace App\Filament\Resources\HasilOptimasis\Pages;

use App\Filament\Resources\HasilOptimasis\HasilOptimasiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHasilOptimasis extends ListRecords
{
    protected static string $resource = HasilOptimasiResource::class;

    protected static ?string $title = 'Laporan Hasil Optimasi';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Hasil Optimasi')
                ->visible(fn (): bool => static::getResource()::canCreate()),
        ];
    }
}
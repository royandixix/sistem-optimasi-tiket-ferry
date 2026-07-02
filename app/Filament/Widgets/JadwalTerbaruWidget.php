<?php

namespace App\Filament\Widgets;

use App\Models\JadwalKeberangkatan;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class JadwalTerbaruWidget extends TableWidget
{
    protected static ?string $heading = 'Jadwal Keberangkatan Terbaru';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->canViewReportData() ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                JadwalKeberangkatan::query()
                    ->with(['kapal', 'rute'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('kapal.nama_kapal')
                    ->label('Kapal')
                    ->searchable(),

                TextColumn::make('rute.pelabuhan_asal')
                    ->label('Asal'),

                TextColumn::make('rute.pelabuhan_tujuan')
                    ->label('Tujuan'),

                TextColumn::make('tanggal_berangkat')
                    ->label('Tanggal')
                    ->date('d M Y'),

                TextColumn::make('jam_berangkat')
                    ->label('Jam')
                    ->time('H:i'),

                TextColumn::make('kapasitas_total')
                    ->label('Kapasitas')
                    ->numeric(),

                TextColumn::make('sisa_kapasitas')
                    ->label('Sisa')
                    ->numeric(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'tersedia' => 'Tersedia',
                        'penuh' => 'Penuh',
                        'selesai' => 'Selesai',
                        'batal' => 'Batal',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'penuh' => 'warning',
                        'selesai' => 'gray',
                        'batal' => 'danger',
                        default => 'gray',
                    }),
            ]);
    }
}
<?php

namespace App\Filament\Resources\HasilOptimasis\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class HasilOptimasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('jadwal.kapal.nama_kapal')
                    ->label('Kapal')
                    ->searchable(),

                TextColumn::make('jadwal.rute.pelabuhan_asal')
                    ->label('Asal')
                    ->searchable(),

                TextColumn::make('jadwal.rute.pelabuhan_tujuan')
                    ->label('Tujuan')
                    ->searchable(),

                TextColumn::make('jadwal.tanggal_berangkat')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('jadwal.jam_berangkat')
                    ->label('Jam')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('metode')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'fcfs' => 'FCFS',
                        'greedy' => 'Greedy Heuristik',
                        default => strtoupper($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'fcfs' => 'gray',
                        'greedy' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('total_pemesanan')
                    ->label('Total Pemesanan')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total_tiket_diminta')
                    ->label('Tiket Diminta')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total_tiket_diterima')
                    ->label('Tiket Diterima')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('total_tiket_ditolak')
                    ->label('Tiket Ditolak')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('kapasitas_kapal')
                    ->label('Kapasitas Kapal')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('kapasitas_terpakai')
                    ->label('Kapasitas Terpakai')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('load_factor')
                    ->label('Load Factor')
                    ->numeric(decimalPlaces: 2)
                    ->suffix('%')
                    ->sortable(),

                TextColumn::make('waktu_proses_ms')
                    ->label('Waktu Proses')
                    ->numeric(decimalPlaces: 4)
                    ->suffix(' ms')
                    ->sortable(),

                TextColumn::make('diprosesOleh.name')
                    ->label('Diproses Oleh')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Proses')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('metode')
                    ->label('Filter Metode')
                    ->options([
                        'fcfs' => 'FCFS',
                        'greedy' => 'Greedy Heuristik',
                    ]),
            ])
            ->emptyStateHeading('Belum ada data hasil optimasi')
            ->emptyStateDescription('Pilih tombol Proses FCFS atau Proses Greedy untuk menghasilkan laporan optimasi otomatis.');
    }
}
<?php

namespace App\Filament\Resources\AlokasiTikets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AlokasiTiketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('pemesananTiket.kode_pemesanan')
                    ->label('Kode Pemesanan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pemesananTiket.penumpang.nama_penumpang')
                    ->label('Nama Penumpang')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jadwal.kapal.nama_kapal')
                    ->label('Kapal')
                    ->searchable()
                    ->sortable(),

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

                TextColumn::make('jumlah_dialokasikan')
                    ->label('Tiket Dialokasikan')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('nilai_prioritas')
                    ->label('Nilai Prioritas')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sisa_kapasitas_sebelum')
                    ->label('Sisa Sebelum')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sisa_kapasitas_sesudah')
                    ->label('Sisa Sesudah')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('status_alokasi')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
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

                SelectFilter::make('status_alokasi')
                    ->label('Filter Status')
                    ->options([
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ubah'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Data Terpilih'),
                ]),
            ])
            ->emptyStateHeading('Belum ada data alokasi tiket')
            ->emptyStateDescription('Data alokasi tiket akan tampil setelah proses FCFS atau Greedy Heuristik dijalankan.');
    }
}
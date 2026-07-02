<?php

namespace App\Filament\Resources\PemesananTikets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
class PemesananTiketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('waktu_pemesanan', 'desc')
            ->columns([
                TextColumn::make('kode_pemesanan')
                    ->label('Kode Pemesanan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('penumpang.nama_penumpang')
                    ->label('Penumpang')
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
                    ->label('Tanggal Berangkat')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('jadwal.jam_berangkat')
                    ->label('Jam')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('jumlah_tiket')
                    ->label('Jumlah Tiket')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('waktu_pemesanan')
                    ->label('Waktu Pesan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('status_pemesanan')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('metode_alokasi')
                    ->label('Metode')
                    ->badge()
                    ->placeholder('Belum diproses')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'fcfs' => 'FCFS',
                        'greedy' => 'Greedy Heuristik',
                        default => 'Belum diproses',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'fcfs' => 'gray',
                        'greedy' => 'success',
                        default => 'warning',
                    })
                    ->sortable(),

                TextColumn::make('pembuat.name')
                    ->label('Diinput Oleh')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status_pemesanan')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ]),

                SelectFilter::make('metode_alokasi')
                    ->label('Filter Metode')
                    ->options([
                        'fcfs' => 'FCFS',
                        'greedy' => 'Greedy Heuristik',
                    ]),

                SelectFilter::make('jadwal_id')
                    ->label('Filter Jadwal')
                    ->relationship('jadwal', 'tanggal_berangkat')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Ubah'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Data Terpilih')
                        ->visible(fn (): bool => auth()->user()?->isSuperAdmin()),
                ]),
            ])
            ->emptyStateHeading('Belum ada data pemesanan tiket')
            ->emptyStateDescription('Tambahkan data pemesanan tiket untuk diproses menggunakan FCFS atau Greedy Heuristik.');
    }
}
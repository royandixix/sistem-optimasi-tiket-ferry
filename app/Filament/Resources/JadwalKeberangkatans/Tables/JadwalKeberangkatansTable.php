<?php

namespace App\Filament\Resources\JadwalKeberangkatans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class JadwalKeberangkatansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal_berangkat', 'desc')
            ->columns([
                TextColumn::make('kapal.nama_kapal')
                    ->label('Kapal')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('rute.pelabuhan_asal')
                    ->label('Asal')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('rute.pelabuhan_tujuan')
                    ->label('Tujuan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal_berangkat')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('jam_berangkat')
                    ->label('Jam')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('kapasitas_total')
                    ->label('Kapasitas Total')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('kapasitas_terpakai')
                    ->label('Terpakai')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('sisa_kapasitas')
                    ->label('Sisa')
                    ->numeric()
                    ->sortable(),

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
                    })
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
                SelectFilter::make('kapal_id')
                    ->label('Filter Kapal')
                    ->relationship('kapal', 'nama_kapal')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('rute_id')
                    ->label('Filter Rute')
                    ->relationship('rute', 'pelabuhan_asal')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'penuh' => 'Penuh',
                        'selesai' => 'Selesai',
                        'batal' => 'Batal',
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
            ->emptyStateHeading('Belum ada jadwal keberangkatan')
            ->emptyStateDescription('Tambahkan jadwal kapal ferry berdasarkan kapal, rute, tanggal, dan jam keberangkatan.');
    }
}
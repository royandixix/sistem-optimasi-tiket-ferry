<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Akun')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nama Lengkap'),

                        TextEntry::make('email')
                            ->label('Email'),

                        TextEntry::make('email_verified_at')
                            ->label('Verifikasi Email')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('created_at')
                            ->label('Tanggal Dibuat')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diubah')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),
                    ]),

                Section::make('Hak Akses')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextEntry::make('role')
                            ->label('Role')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'super_admin' => 'Super Admin',
                                'admin' => 'Admin',
                                'petugas' => 'Petugas Validasi',
                                'pimpinan' => 'Pimpinan',
                                'penumpang' => 'Penumpang',
                                default => ucfirst($state),
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'super_admin' => 'danger',
                                'admin' => 'warning',
                                'petugas' => 'success',
                                'pimpinan' => 'info',
                                'penumpang' => 'gray',
                                default => 'gray',
                            }),

                        TextEntry::make('status')
                            ->label('Status Akun')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Nonaktif',
                                default => ucfirst($state),
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'aktif' => 'success',
                                'nonaktif' => 'danger',
                                default => 'gray',
                            }),
                    ]),
            ]);
    }
}

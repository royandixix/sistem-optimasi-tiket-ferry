<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun')
                    ->description('Data akun digunakan untuk login ke sistem sesuai role pengguna.')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->placeholder('Masukkan nama lengkap')
                            ->maxLength(150)
                            ->required(),

                        TextInput::make('email')
                            ->label('Email')
                            ->placeholder('contoh@email.com')
                            ->email()
                            ->maxLength(150)
                            ->unique(ignoreRecord: true)
                            ->required(),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->placeholder('Masukkan password')
                            ->helperText('Kosongkan jika tidak ingin mengubah password.')
                            ->minLength(8)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state)),

                        DateTimePicker::make('email_verified_at')
                            ->label('Waktu Verifikasi Email')
                            ->native(false)
                            ->seconds(false)
                            ->placeholder('Opsional')
                            ->helperText('Boleh dikosongkan jika verifikasi email belum digunakan.'),
                    ]),

                Section::make('Role dan Status Akses')
                    ->description('Tentukan hak akses user berdasarkan kebutuhan sistem.')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('role')
                            ->label('Role User')
                            ->options([
                                'super_admin' => 'Super Admin',
                                'admin' => 'Admin/Petugas',
                                'pimpinan' => 'Pimpinan',
                                'penumpang' => 'Penumpang',
                            ])
                            ->native(false)
                            ->default('penumpang')
                            ->helperText('Super Admin, Admin/Petugas, dan Pimpinan masuk Filament. Penumpang menggunakan halaman Blade.')
                            ->required(),

                        Select::make('status')
                            ->label('Status Akun')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Nonaktif',
                            ])
                            ->native(false)
                            ->default('aktif')
                            ->required(),
                    ]),
            ]);
    }
}
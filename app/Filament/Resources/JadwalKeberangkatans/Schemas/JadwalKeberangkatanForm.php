<?php

namespace App\Filament\Resources\JadwalKeberangkatans\Schemas;

use App\Models\Kapal;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
class JadwalKeberangkatanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Keberangkatan')
                    ->description('Atur kapal, rute, tanggal, dan jam keberangkatan ferry.')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Select::make('kapal_id')
                            ->label('Kapal')
                            ->relationship('kapal', 'nama_kapal')
                            ->getOptionLabelFromRecordUsing(function ($record): string {
                                return "{$record->nama_kapal} - Kapasitas {$record->kapasitas_penumpang} penumpang";
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get): void {
                                $kapasitas = Kapal::find($state)?->kapasitas_penumpang ?? 0;
                                $kapasitasTerpakai = (int) ($get('kapasitas_terpakai') ?? 0);

                                $set('kapasitas_total', $kapasitas);
                                $set('sisa_kapasitas', max($kapasitas - $kapasitasTerpakai, 0));
                            })
                            ->required(),

                        Select::make('rute_id')
                            ->label('Rute Penyeberangan')
                            ->relationship('rute', 'pelabuhan_asal')
                            ->getOptionLabelFromRecordUsing(function ($record): string {
                                return "{$record->pelabuhan_asal} ke {$record->pelabuhan_tujuan}";
                            })
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('tanggal_berangkat')
                            ->label('Tanggal Berangkat')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->required(),

                        TimePicker::make('jam_berangkat')
                            ->label('Jam Berangkat')
                            ->native(false)
                            ->seconds(false)
                            ->required(),
                    ]),

                Section::make('Informasi Kapasitas Kapal')
                    ->description('Kapasitas total otomatis mengikuti kapal yang dipilih. Sisa kapasitas dihitung dari kapasitas total dikurangi kapasitas terpakai.')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextInput::make('kapasitas_total')
                            ->label('Kapasitas Total')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->disabled()
                            ->dehydrated(true)
                            ->required(),

                        TextInput::make('kapasitas_terpakai')
                            ->label('Kapasitas Terpakai')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function ($state, $set, $get): void {
                                $kapasitasTotal = (int) ($get('kapasitas_total') ?? 0);
                                $kapasitasTerpakai = (int) ($state ?? 0);

                                $set('sisa_kapasitas', max($kapasitasTotal - $kapasitasTerpakai, 0));
                            })
                            ->required(),

                        TextInput::make('sisa_kapasitas')
                            ->label('Sisa Kapasitas')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->disabled()
                            ->dehydrated(true)
                            ->required(),

                        Select::make('status')
                            ->label('Status Jadwal')
                            ->options([
                                'tersedia' => 'Tersedia',
                                'penuh' => 'Penuh',
                                'selesai' => 'Selesai',
                                'batal' => 'Batal',
                            ])
                            ->native(false)
                            ->default('tersedia')
                            ->required(),
                    ]),
            ]);
    }
}
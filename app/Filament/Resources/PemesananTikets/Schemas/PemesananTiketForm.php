<?php

namespace App\Filament\Resources\PemesananTikets\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
class PemesananTiketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pemesanan')
                    ->description('Data ini digunakan sebagai dasar proses alokasi tiket kapal ferry.')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('kode_pemesanan')
                            ->label('Kode Pemesanan')
                            ->default(fn () => 'PM-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)))
                            ->disabled()
                            ->dehydrated(true)
                            ->unique(ignoreRecord: true)
                            ->required(),

                        DateTimePicker::make('waktu_pemesanan')
                            ->label('Waktu Pemesanan')
                            ->native(false)
                            ->seconds(false)
                            ->default(now())
                            ->required(),

                        Select::make('penumpang_id')
                            ->label('Nama Penumpang')
                            ->relationship('penumpang', 'nama_penumpang')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('jumlah_tiket')
                            ->label('Jumlah Tiket')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),
                    ]),

                Section::make('Jadwal Keberangkatan')
                    ->description('Pilih jadwal kapal yang akan digunakan untuk pemesanan tiket.')
                    ->columnSpanFull()
                    ->columns(1)
                    ->schema([
                        Select::make('jadwal_id')
                            ->label('Jadwal Keberangkatan')
                            ->relationship('jadwal', 'tanggal_berangkat')
                            ->getOptionLabelFromRecordUsing(function ($record): string {
                                $tanggal = optional($record->tanggal_berangkat)->format('d-m-Y') ?? '-';
                                $jam = $record->jam_berangkat ?? '-';
                                $kapal = $record->kapal?->nama_kapal ?? '-';
                                $asal = $record->rute?->pelabuhan_asal ?? '-';
                                $tujuan = $record->rute?->pelabuhan_tujuan ?? '-';
                                $sisa = $record->sisa_kapasitas ?? 0;

                                return "{$tanggal} {$jam} - {$kapal} ({$asal} ke {$tujuan}) | Sisa {$sisa} kursi";
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Section::make('Status dan Metode')
                    ->description('Status pemesanan akan menjadi dasar dalam proses alokasi tiket menggunakan FCFS atau Greedy Heuristik.')
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Select::make('status_pemesanan')
                            ->label('Status Pemesanan')
                            ->options([
                                'pending' => 'Pending',
                                'diterima' => 'Diterima',
                                'ditolak' => 'Ditolak',
                            ])
                            ->native(false)
                            ->default('pending')
                            ->required(),

                        Select::make('metode_alokasi')
                            ->label('Metode Alokasi')
                            ->options([
                                'fcfs' => 'First Come First Served (FCFS)',
                                'greedy' => 'Greedy Heuristik',
                            ])
                            ->placeholder('Belum diproses')
                            ->native(false),

                        Select::make('created_by')
                            ->label('Diinput Oleh')
                            ->relationship('pembuat', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->id())
                            ->disabled()
                            ->dehydrated(true),
                    ]),

                Section::make('Catatan Tambahan')
                    ->columnSpanFull()
                    ->schema([
                        Textarea::make('catatan')
                            ->label('Catatan')
                            ->placeholder('Masukkan catatan jika diperlukan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
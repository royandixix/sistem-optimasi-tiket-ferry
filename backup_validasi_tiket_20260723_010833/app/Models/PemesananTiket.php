<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PemesananTiket extends Model
{
    protected $fillable = [
        'kode_pemesanan',
        'qr_token',
        'penumpang_id',
        'jadwal_id',
        'jumlah_tiket',
        'waktu_pemesanan',
        'status_pemesanan',
        'metode_alokasi',
        'created_by',
        'catatan',
        'digunakan_pada',
        'divalidasi_oleh',
    ];

    protected function casts(): array
    {
        return [
            'waktu_pemesanan' => 'datetime',
            'jumlah_tiket' => 'integer',
            'digunakan_pada' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PemesananTiket $pemesanan): void {
            if (blank($pemesanan->qr_token)) {
                $pemesanan->qr_token = (string) Str::uuid();
            }
        });
    }

    public function penumpang(): BelongsTo
    {
        return $this->belongsTo(Penumpang::class);
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(
            JadwalKeberangkatan::class,
            'jadwal_id'
        );
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function divalidasiOleh(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'divalidasi_oleh'
        );
    }

    public function alokasiTikets(): HasMany
    {
        return $this->hasMany(AlokasiTiket::class);
    }

    public function validasiTikets(): HasMany
    {
        return $this->hasMany(ValidasiTiket::class);
    }
}
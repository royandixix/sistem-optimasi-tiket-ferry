<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PemesananTiket extends Model
{
    protected $fillable = [
        'kode_pemesanan',
        'penumpang_id',
        'jadwal_id',
        'jumlah_tiket',
        'waktu_pemesanan',
        'status_pemesanan',
        'metode_alokasi',
        'created_by',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'waktu_pemesanan' => 'datetime',
            'jumlah_tiket' => 'integer',
        ];
    }

    public function penumpang(): BelongsTo
    {
        return $this->belongsTo(Penumpang::class);
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(JadwalKeberangkatan::class, 'jadwal_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function alokasiTikets(): HasMany
    {
        return $this->hasMany(AlokasiTiket::class);
    }
}
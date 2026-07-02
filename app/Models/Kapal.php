<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kapal extends Model
{
    protected $fillable = [
        'kode_kapal',
        'nama_kapal',
        'kapasitas_penumpang',
        'status',
    ];

    public function jadwalKeberangkatans(): HasMany
    {
        return $this->hasMany(JadwalKeberangkatan::class);
    }
}
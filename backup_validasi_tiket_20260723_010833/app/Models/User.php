<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->status === 'aktif'
            && $this->isInternalUser();
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    public function isPenumpang(): bool
    {
        return $this->role === 'penumpang';
    }

    public function isInternalUser(): bool
    {
        return in_array($this->role, [
            'super_admin',
            'admin',
            'petugas',
            'pimpinan',
        ], true);
    }

    public function canManageUsers(): bool
    {
        return $this->isSuperAdmin();
    }

    public function canViewOperationalData(): bool
    {
        return in_array($this->role, [
            'super_admin',
            'admin',
            'pimpinan',
        ], true);
    }

    public function canManageOperationalData(): bool
    {
        return in_array($this->role, [
            'super_admin',
            'admin',
        ], true);
    }

    public function canManageBookingData(): bool
    {
        return in_array($this->role, [
            'super_admin',
            'admin',
        ], true);
    }

    public function canViewOptimizationData(): bool
    {
        return in_array($this->role, [
            'super_admin',
            'admin',
            'pimpinan',
        ], true);
    }

    public function canManageOptimizationData(): bool
    {
        return in_array($this->role, [
            'super_admin',
            'admin',
        ], true);
    }

    public function canViewReportData(): bool
    {
        return in_array($this->role, [
            'super_admin',
            'admin',
            'pimpinan',
        ], true);
    }

    public function canValidateTickets(): bool
    {
        return in_array($this->role, [
            'super_admin',
            'petugas',
        ], true);
    }

    public function canDeleteImportantData(): bool
    {
        return $this->isSuperAdmin();
    }

    public function penumpang(): HasOne
    {
        return $this->hasOne(Penumpang::class);
    }

    public function pemesananTikets(): HasMany
    {
        return $this->hasMany(PemesananTiket::class, 'created_by');
    }

    public function alokasiDiproses(): HasMany
    {
        return $this->hasMany(AlokasiTiket::class, 'diproses_oleh');
    }

    public function hasilOptimasiDiproses(): HasMany
    {
        return $this->hasMany(HasilOptimasi::class, 'diproses_oleh');
    }

    public function validasiDilakukan(): HasMany
    {
        return $this->hasMany(ValidasiTiket::class, 'divalidasi_oleh');
    }

    public function tiketDivalidasi(): HasMany
    {
        return $this->hasMany(PemesananTiket::class, 'divalidasi_oleh');
    }
}
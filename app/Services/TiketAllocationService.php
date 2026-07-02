<?php

namespace App\Services;

use App\Models\AlokasiTiket;
use App\Models\HasilOptimasi;
use App\Models\JadwalKeberangkatan;
use App\Models\PemesananTiket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TiketAllocationService
{
    public function process(int $jadwalId, string $metode): HasilOptimasi
    {
        if (! in_array($metode, ['fcfs', 'greedy'])) {
            throw new InvalidArgumentException('Metode optimasi tidak valid.');
        }

        $startTime = microtime(true);

        return DB::transaction(function () use ($jadwalId, $metode, $startTime) {
            $jadwal = JadwalKeberangkatan::query()
                ->lockForUpdate()
                ->findOrFail($jadwalId);

            $kapasitasKapal = (int) $jadwal->kapasitas_total;
            $sisaKapasitas = $kapasitasKapal;

            AlokasiTiket::query()
                ->where('jadwal_id', $jadwal->id)
                ->where('metode', $metode)
                ->delete();

            $pemesanans = $this->getPemesanans($jadwal->id, $metode);

            $totalPemesanan = $pemesanans->count();
            $totalTiketDiminta = 0;
            $totalTiketDiterima = 0;
            $totalTiketDitolak = 0;

            foreach ($pemesanans as $index => $pemesanan) {
                $jumlahTiket = (int) $pemesanan->jumlah_tiket;
                $totalTiketDiminta += $jumlahTiket;

                $diterima = $jumlahTiket <= $sisaKapasitas;
                $jumlahDialokasikan = $diterima ? $jumlahTiket : 0;
                $statusAlokasi = $diterima ? 'diterima' : 'ditolak';
                $sisaSebelum = $sisaKapasitas;

                if ($diterima) {
                    $sisaKapasitas -= $jumlahTiket;
                    $totalTiketDiterima += $jumlahTiket;
                } else {
                    $totalTiketDitolak += $jumlahTiket;
                }

                AlokasiTiket::create([
                    'pemesanan_tiket_id' => $pemesanan->id,
                    'jadwal_id' => $jadwal->id,
                    'metode' => $metode,
                    'jumlah_dialokasikan' => $jumlahDialokasikan,
                    'nilai_prioritas' => $this->getNilaiPrioritas($pemesanan, $metode, $index),
                    'sisa_kapasitas_sebelum' => $sisaSebelum,
                    'sisa_kapasitas_sesudah' => $sisaKapasitas,
                    'status_alokasi' => $statusAlokasi,
                    'diproses_oleh' => Auth::id(),
                ]);

                $pemesanan->update([
                    'status_pemesanan' => $statusAlokasi,
                    'metode_alokasi' => $metode,
                ]);
            }

            $kapasitasTerpakai = $kapasitasKapal - $sisaKapasitas;
            $loadFactor = $kapasitasKapal > 0
                ? round(($kapasitasTerpakai / $kapasitasKapal) * 100, 2)
                : 0;

            $jadwal->update([
                'kapasitas_terpakai' => $kapasitasTerpakai,
                'sisa_kapasitas' => $sisaKapasitas,
                'status' => $sisaKapasitas <= 0 && $kapasitasKapal > 0 ? 'penuh' : 'tersedia',
            ]);

            $waktuProsesMs = round((microtime(true) - $startTime) * 1000, 4);

            return HasilOptimasi::updateOrCreate(
                [
                    'jadwal_id' => $jadwal->id,
                    'metode' => $metode,
                ],
                [
                    'total_pemesanan' => $totalPemesanan,
                    'total_tiket_diminta' => $totalTiketDiminta,
                    'total_tiket_diterima' => $totalTiketDiterima,
                    'total_tiket_ditolak' => $totalTiketDitolak,
                    'kapasitas_kapal' => $kapasitasKapal,
                    'kapasitas_terpakai' => $kapasitasTerpakai,
                    'load_factor' => $loadFactor,
                    'waktu_proses_ms' => $waktuProsesMs,
                    'diproses_oleh' => Auth::id(),
                ]
            );
        });
    }

    private function getPemesanans(int $jadwalId, string $metode)
    {
        $query = PemesananTiket::query()
            ->where('jadwal_id', $jadwalId);

        if ($metode === 'fcfs') {
            return $query
                ->orderBy('waktu_pemesanan')
                ->orderBy('id')
                ->get();
        }

        return $query
            ->orderByDesc('jumlah_tiket')
            ->orderBy('waktu_pemesanan')
            ->orderBy('id')
            ->get();
    }

    private function getNilaiPrioritas(PemesananTiket $pemesanan, string $metode, int $index): int
    {
        if ($metode === 'greedy') {
            return (int) $pemesanan->jumlah_tiket;
        }

        return $index + 1;
    }
}

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
        if (! in_array($metode, ['fcfs', 'greedy'], true)) {
            throw new InvalidArgumentException('Metode alokasi tidak valid.');
        }

        return DB::transaction(function () use ($jadwalId, $metode) {
            $startTime = microtime(true);

            $jadwal = JadwalKeberangkatan::query()
                ->lockForUpdate()
                ->findOrFail($jadwalId);

            $kapasitasKapal = (int) $jadwal->kapasitas_total;
            $sisaKapasitas = $kapasitasKapal;

            // Hapus hasil alokasi lama untuk jadwal dan metode yang sama
            AlokasiTiket::query()
                ->where('jadwal_id', $jadwal->id)
                ->where('metode', $metode)
                ->delete();

            $query = PemesananTiket::query()
                ->where('jadwal_id', $jadwal->id);

            // FCFS: berdasarkan waktu pemesanan paling awal
            if ($metode === 'fcfs') {
                $query->orderBy('waktu_pemesanan', 'asc')
                    ->orderBy('id', 'asc');
            }

            // Greedy: prioritas jumlah tiket terbesar yang masih bisa masuk kapasitas
            if ($metode === 'greedy') {
                $query->orderBy('jumlah_tiket', 'desc')
                    ->orderBy('waktu_pemesanan', 'asc')
                    ->orderBy('id', 'asc');
            }

            $pemesanans = $query->lockForUpdate()->get();

            $totalPemesanan = $pemesanans->count();
            $totalTiketDiminta = 0;
            $totalTiketDiterima = 0;
            $totalTiketDitolak = 0;
            $urutan = 1;

            foreach ($pemesanans as $pemesanan) {
                $jumlahTiket = (int) $pemesanan->jumlah_tiket;
                $totalTiketDiminta += $jumlahTiket;

                $sisaSebelum = $sisaKapasitas;

                if ($jumlahTiket <= $sisaKapasitas) {
                    $statusAlokasi = 'diterima';
                    $jumlahDialokasikan = $jumlahTiket;
                    $sisaKapasitas -= $jumlahTiket;
                    $totalTiketDiterima += $jumlahTiket;
                } else {
                    $statusAlokasi = 'ditolak';
                    $jumlahDialokasikan = 0;
                    $totalTiketDitolak += $jumlahTiket;
                }

                AlokasiTiket::create([
                    'pemesanan_tiket_id' => $pemesanan->id,
                    'jadwal_id' => $jadwal->id,
                    'metode' => $metode,
                    'jumlah_dialokasikan' => $jumlahDialokasikan,
                    'nilai_prioritas' => $metode === 'greedy' ? $jumlahTiket : $urutan,
                    'sisa_kapasitas_sebelum' => $sisaSebelum,
                    'sisa_kapasitas_sesudah' => $sisaKapasitas,
                    'status_alokasi' => $statusAlokasi,
                    'diproses_oleh' => Auth::id(),
                ]);

                // Status pemesanan mengikuti metode proses terakhir
                $pemesanan->update([
                    'status_pemesanan' => $statusAlokasi,
                    'metode_alokasi' => $metode,
                ]);

                $urutan++;
            }

            $kapasitasTerpakai = $totalTiketDiterima;

            $loadFactor = $kapasitasKapal > 0
                ? ($kapasitasTerpakai / $kapasitasKapal) * 100
                : 0;

            $waktuProsesMs = (microtime(true) - $startTime) * 1000;

            $jadwal->update([
                'kapasitas_terpakai' => $kapasitasTerpakai,
                'sisa_kapasitas' => max($kapasitasKapal - $kapasitasTerpakai, 0),
                'status' => $kapasitasTerpakai >= $kapasitasKapal ? 'penuh' : 'tersedia',
            ]);

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
                    'load_factor' => round($loadFactor, 2),
                    'waktu_proses_ms' => round($waktuProsesMs, 4),
                    'diproses_oleh' => Auth::id(),
                ]
            );
        });
    }
}
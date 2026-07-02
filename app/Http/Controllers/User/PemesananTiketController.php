<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JadwalKeberangkatan;
use App\Models\PemesananTiket;
use App\Models\Penumpang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PemesananTiketController extends Controller
{
    public function index()
    {
        $penumpang = $this->getOrCreatePenumpang();

        $pemesanans = PemesananTiket::query()
            ->with(['jadwal.kapal', 'jadwal.rute'])
            ->where('penumpang_id', $penumpang->id)
            ->latest()
            ->paginate(10);

        return view('user.pemesanan.index', compact('pemesanans'));
    }

    public function create()
    {
        $jadwals = $this->getAvailableJadwals();

        return view('user.pemesanan.create', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jadwal_id' => ['required', 'exists:jadwal_keberangkatans,id'],
            'jumlah_tiket' => ['required', 'integer', 'min:1'],
            'catatan' => ['nullable', 'string'],
        ], [
            'jadwal_id.required' => 'Jadwal keberangkatan wajib dipilih.',
            'jadwal_id.exists' => 'Jadwal keberangkatan tidak valid.',
            'jumlah_tiket.required' => 'Jumlah tiket wajib diisi.',
            'jumlah_tiket.integer' => 'Jumlah tiket harus berupa angka.',
            'jumlah_tiket.min' => 'Jumlah tiket minimal 1.',
        ]);

        $penumpang = $this->getOrCreatePenumpang();

        $jadwal = JadwalKeberangkatan::query()
            ->where('id', $validated['jadwal_id'])
            ->where('status', 'tersedia')
            ->firstOrFail();

        if ((int) $validated['jumlah_tiket'] > (int) $jadwal->kapasitas_total) {
            return back()
                ->withInput()
                ->with('error', 'Jumlah tiket tidak boleh melebihi kapasitas kapal.');
        }

        DB::transaction(function () use ($validated, $penumpang) {
            PemesananTiket::create([
                'kode_pemesanan' => 'PM-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                'penumpang_id' => $penumpang->id,
                'jadwal_id' => $validated['jadwal_id'],
                'jumlah_tiket' => $validated['jumlah_tiket'],
                'waktu_pemesanan' => now(),
                'status_pemesanan' => 'pending',
                'metode_alokasi' => null,
                'created_by' => Auth::id(),
                'catatan' => $validated['catatan'] ?? null,
            ]);
        });

        return redirect()
            ->route('user.pemesanan.index')
            ->with('success', 'Pemesanan tiket berhasil dibuat dan menunggu proses alokasi.');
    }

    public function show(PemesananTiket $pemesanan)
    {
        $this->authorizeOwnedBooking($pemesanan);

        $pemesanan->load(['jadwal.kapal', 'jadwal.rute', 'alokasiTikets']);

        return view('user.pemesanan.show', compact('pemesanan'));
    }

    public function edit(PemesananTiket $pemesanan)
    {
        $this->authorizeOwnedBooking($pemesanan);

        if ($pemesanan->status_pemesanan !== 'pending') {
            return redirect()
                ->route('user.pemesanan.show', $pemesanan)
                ->with('error', 'Pemesanan yang sudah diproses tidak dapat diubah.');
        }

        $jadwals = $this->getAvailableJadwals();

        return view('user.pemesanan.edit', compact('pemesanan', 'jadwals'));
    }

    public function update(Request $request, PemesananTiket $pemesanan)
    {
        $this->authorizeOwnedBooking($pemesanan);

        if ($pemesanan->status_pemesanan !== 'pending') {
            return redirect()
                ->route('user.pemesanan.show', $pemesanan)
                ->with('error', 'Pemesanan yang sudah diproses tidak dapat diubah.');
        }

        $validated = $request->validate([
            'jadwal_id' => ['required', 'exists:jadwal_keberangkatans,id'],
            'jumlah_tiket' => ['required', 'integer', 'min:1'],
            'catatan' => ['nullable', 'string'],
        ]);

        $jadwal = JadwalKeberangkatan::query()
            ->where('id', $validated['jadwal_id'])
            ->where('status', 'tersedia')
            ->firstOrFail();

        if ((int) $validated['jumlah_tiket'] > (int) $jadwal->kapasitas_total) {
            return back()
                ->withInput()
                ->with('error', 'Jumlah tiket tidak boleh melebihi kapasitas kapal.');
        }

        $pemesanan->update([
            'jadwal_id' => $validated['jadwal_id'],
            'jumlah_tiket' => $validated['jumlah_tiket'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()
            ->route('user.pemesanan.index')
            ->with('success', 'Pemesanan tiket berhasil diperbarui.');
    }

    private function getOrCreatePenumpang(): Penumpang
    {
        $user = Auth::user();

        return Penumpang::firstOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'nama_penumpang' => $user->name,
                'nik' => null,
                'jenis_kelamin' => null,
                'no_hp' => null,
                'alamat' => null,
            ]
        );
    }

    private function authorizeOwnedBooking(PemesananTiket $pemesanan): void
    {
        $penumpang = $this->getOrCreatePenumpang();

        if ((int) $pemesanan->penumpang_id !== (int) $penumpang->id) {
            abort(403, 'Anda tidak memiliki akses ke pemesanan ini.');
        }
    }

    private function getAvailableJadwals()
    {
        return JadwalKeberangkatan::query()
            ->with(['kapal', 'rute'])
            ->where('status', 'tersedia')
            ->whereDate('tanggal_berangkat', '>=', now()->toDateString())
            ->orderBy('tanggal_berangkat')
            ->orderBy('jam_berangkat')
            ->get();
    }
}
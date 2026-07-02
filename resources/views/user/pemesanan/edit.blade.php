@extends('user.layouts.app')

@section('title', 'Edit Pemesanan')
@section('page-title', 'Edit Pemesanan Tiket')
@section('page-description', 'Perbarui data pemesanan tiket selama status pemesanan masih menunggu proses.')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <h4 class="mb-2">Edit Pemesanan</h4>
                <p class="text-muted mb-4">
                    Anda hanya dapat mengubah pemesanan yang masih berstatus menunggu proses.
                </p>

                <form method="POST" action="{{ route('user.pemesanan.update', $pemesanan) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Kode Pemesanan</label>
                        <input type="text"
                               class="form-control"
                               value="{{ $pemesanan->kode_pemesanan }}"
                               disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jadwal Keberangkatan</label>
                        <select name="jadwal_id" class="form-select" required>
                            <option value="">Pilih jadwal keberangkatan</option>

                            @foreach ($jadwals as $jadwal)
                                <option value="{{ $jadwal->id }}" @selected(old('jadwal_id', $pemesanan->jadwal_id) == $jadwal->id)>
                                    {{ $jadwal->tanggal_berangkat }}
                                    {{ $jadwal->jam_berangkat }}
                                    -
                                    {{ optional($jadwal->rute)->pelabuhan_asal ?? '-' }}
                                    ke
                                    {{ optional($jadwal->rute)->pelabuhan_tujuan ?? '-' }}
                                    |
                                    {{ optional($jadwal->kapal)->nama_kapal ?? 'Kapal' }}
                                    |
                                    Kapasitas: {{ $jadwal->kapasitas_total }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Tiket</label>
                        <input type="number"
                               name="jumlah_tiket"
                               value="{{ old('jumlah_tiket', $pemesanan->jumlah_tiket) }}"
                               class="form-control"
                               min="1"
                               required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Catatan tambahan, boleh dikosongkan">{{ old('catatan', $pemesanan->catatan) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('user.pemesanan.index') }}" class="btn btn-outline-secondary rounded-pill">
                            Kembali
                        </a>

                        <button type="submit" class="btn btn-success rounded-pill">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection

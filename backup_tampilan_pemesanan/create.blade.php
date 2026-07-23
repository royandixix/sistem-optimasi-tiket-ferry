@extends('user.layouts.app')

@section('title', 'Buat Pemesanan')
@section('page-title', 'Buat Pemesanan Tiket')
@section('page-description', 'Pilih jadwal keberangkatan dan masukkan jumlah tiket yang ingin Anda pesan.')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <h4 class="mb-2">Form Pemesanan Tiket</h4>
                <p class="text-muted mb-4">
                    Pastikan jadwal dan jumlah tiket yang Anda pilih sudah sesuai sebelum mengirim pemesanan.
                </p>

                @if ($jadwals->count() > 0)
                    <form method="POST" action="{{ route('user.pemesanan.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Jadwal Keberangkatan</label>
                            <select name="jadwal_id" class="form-select" required>
                                <option value="">Pilih jadwal keberangkatan</option>

                                @foreach ($jadwals as $jadwal)
                                    <option value="{{ $jadwal->id }}" @selected(old('jadwal_id') == $jadwal->id)>
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
                                   value="{{ old('jumlah_tiket', 1) }}"
                                   class="form-control"
                                   min="1"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Catatan tambahan, boleh dikosongkan">{{ old('catatan') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('user.pemesanan.index') }}" class="btn btn-outline-secondary rounded-pill">
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-success rounded-pill">
                                Simpan Pemesanan
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x display-4 text-warning"></i>
                        <h5 class="mt-3">Belum Ada Jadwal Tersedia</h5>
                        <p class="text-muted">
                            Saat ini belum ada jadwal keberangkatan yang tersedia untuk pemesanan.
                        </p>

                        <a href="{{ route('user.pemesanan.index') }}" class="btn btn-outline-secondary rounded-pill">
                            Kembali
                        </a>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>

@endsection

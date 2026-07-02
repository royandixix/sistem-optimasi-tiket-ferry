@extends('user.layouts.app')

@section('title', 'Detail Pemesanan')
@section('page-title', 'Detail Pemesanan Tiket')
@section('page-description', 'Lihat informasi lengkap pemesanan tiket dan status alokasi Anda.')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-9">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">

                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="mb-1">Kode: {{ $pemesanan->kode_pemesanan }}</h4>
                        <p class="text-muted mb-0">
                            Waktu pemesanan:
                            {{ optional($pemesanan->waktu_pemesanan)->format('d/m/Y H:i') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        @if ($pemesanan->status_pemesanan === 'pending')
                            <span class="badge text-bg-warning">Menunggu Proses</span>
                        @elseif ($pemesanan->status_pemesanan === 'diterima')
                            <span class="badge text-bg-success">Diterima</span>
                        @elseif ($pemesanan->status_pemesanan === 'ditolak')
                            <span class="badge text-bg-danger">Ditolak</span>
                        @else
                            <span class="badge text-bg-secondary">
                                {{ ucfirst($pemesanan->status_pemesanan) }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="row gy-4">
                    <div class="col-md-6">
                        <h5>Informasi Jadwal</h5>

                        <p class="mb-2">
                            <strong>Tanggal Berangkat:</strong><br>
                            {{ optional($pemesanan->jadwal)->tanggal_berangkat ?? '-' }}
                        </p>

                        <p class="mb-2">
                            <strong>Jam Berangkat:</strong><br>
                            {{ optional($pemesanan->jadwal)->jam_berangkat ?? '-' }}
                        </p>

                        <p class="mb-2">
                            <strong>Rute:</strong><br>
                            {{ optional(optional($pemesanan->jadwal)->rute)->pelabuhan_asal ?? '-' }}
                            -
                            {{ optional(optional($pemesanan->jadwal)->rute)->pelabuhan_tujuan ?? '-' }}
                        </p>

                        <p class="mb-0">
                            <strong>Kapal:</strong><br>
                            {{ optional(optional($pemesanan->jadwal)->kapal)->nama_kapal ?? '-' }}
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h5>Informasi Pemesanan</h5>

                        <p class="mb-2">
                            <strong>Jumlah Tiket:</strong><br>
                            {{ $pemesanan->jumlah_tiket }} tiket
                        </p>

                        <p class="mb-2">
                            <strong>Metode Alokasi:</strong><br>
                            {{ $pemesanan->metode_alokasi ? strtoupper($pemesanan->metode_alokasi) : '-' }}
                        </p>

                        <p class="mb-0">
                            <strong>Catatan:</strong><br>
                            {{ $pemesanan->catatan ?? '-' }}
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="mb-3">Status Alokasi Tiket</h5>

                @if ($pemesanan->alokasiTikets && $pemesanan->alokasiTikets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Metode</th>
                                    <th>Jumlah Dialokasikan</th>
                                    <th>Status</th>
                                    <th>Sisa Kapasitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pemesanan->alokasiTikets as $alokasi)
                                    <tr>
                                        <td>{{ strtoupper($alokasi->metode) }}</td>
                                        <td>{{ $alokasi->jumlah_dialokasikan }}</td>
                                        <td>
                                            <span class="badge text-bg-success">
                                                {{ ucfirst($alokasi->status_alokasi) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $alokasi->sisa_kapasitas_sebelum }}
                                            ke
                                            {{ $alokasi->sisa_kapasitas_sesudah }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">
                        Pemesanan ini belum memiliki data alokasi tiket.
                    </p>
                @endif
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('user.pemesanan.index') }}" class="btn btn-outline-secondary rounded-pill">
                Kembali
            </a>

            @if ($pemesanan->status_pemesanan === 'pending')
                <a href="{{ route('user.pemesanan.edit', $pemesanan) }}" class="btn btn-success rounded-pill">
                    Edit Pemesanan
                </a>
            @endif
        </div>

    </div>
</div>

@endsection

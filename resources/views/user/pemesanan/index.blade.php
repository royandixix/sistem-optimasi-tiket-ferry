@extends('user.layouts.app')

@section('title', 'Riwayat Pemesanan')
@section('page-title', 'Riwayat Pemesanan Tiket')
@section('page-description', 'Lihat seluruh daftar pemesanan tiket kapal ferry yang pernah Anda buat.')

@section('content')

<div class="row mb-4">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Daftar Pemesanan</h4>
                <p class="text-muted mb-0">
                    Berikut adalah riwayat pemesanan tiket ferry Anda.
                </p>
            </div>

            <a href="{{ route('user.pemesanan.create') }}" class="btn btn-success rounded-pill">
                <i class="bi bi-plus-circle me-1"></i>
                Buat Pemesanan
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        @if ($pemesanans->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Jadwal</th>
                            <th>Kapal</th>
                            <th>Jumlah Tiket</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pemesanans as $pemesanan)
                            <tr>
                                <td>
                                    <strong>{{ $pemesanan->kode_pemesanan }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ optional($pemesanan->waktu_pemesanan)->format('d/m/Y H:i') ?? '-' }}
                                    </small>
                                </td>

                                <td>
                                    <div>
                                        {{ optional($pemesanan->jadwal)->tanggal_berangkat ?? '-' }}
                                        {{ optional($pemesanan->jadwal)->jam_berangkat ?? '' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ optional(optional($pemesanan->jadwal)->rute)->pelabuhan_asal ?? '-' }}
                                        -
                                        {{ optional(optional($pemesanan->jadwal)->rute)->pelabuhan_tujuan ?? '-' }}
                                    </small>
                                </td>

                                <td>
                                    {{ optional(optional($pemesanan->jadwal)->kapal)->nama_kapal ?? '-' }}
                                </td>

                                <td>
                                    {{ $pemesanan->jumlah_tiket }} tiket
                                </td>

                                <td>
                                    @if ($pemesanan->status_pemesanan === 'pending')
                                        <span class="badge text-bg-warning">Menunggu</span>
                                    @elseif ($pemesanan->status_pemesanan === 'diterima')
                                        <span class="badge text-bg-success">Diterima</span>
                                    @elseif ($pemesanan->status_pemesanan === 'ditolak')
                                        <span class="badge text-bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge text-bg-secondary">
                                            {{ ucfirst($pemesanan->status_pemesanan) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('user.pemesanan.show', $pemesanan) }}" class="btn btn-sm btn-outline-success rounded-pill">
                                        Detail
                                    </a>

                                    @if ($pemesanan->status_pemesanan === 'pending')
                                        <a href="{{ route('user.pemesanan.edit', $pemesanan) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                                            Edit
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $pemesanans->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-ticket-perforated display-4 text-success"></i>
                <h5 class="mt-3">Belum Ada Pemesanan</h5>
                <p class="text-muted">
                    Anda belum memiliki riwayat pemesanan tiket.
                    Silakan buat pemesanan tiket terlebih dahulu.
                </p>

                <a href="{{ route('user.pemesanan.create') }}" class="btn btn-success rounded-pill">
                    Buat Pemesanan Tiket
                </a>
            </div>
        @endif

    </div>
</div>

@endsection

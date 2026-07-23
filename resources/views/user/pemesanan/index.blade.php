@extends('user.layouts.app')

@section('title', 'Riwayat Pemesanan')
@section('page-title', 'Riwayat Pemesanan Tiket')
@section(
    'page-description',
    'Lihat kapal, jadwal perjalanan, dan status pemesanan tiket Anda.'
)

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            Daftar Pemesanan
        </h4>

        <p class="text-muted small mb-0">
            Informasi perjalanan dan status tiket Anda.
        </p>
    </div>

    <div class="d-grid d-md-block">
        <a
            href="{{ route('user.pemesanan.create') }}"
            class="btn btn-success"
        >
            <i class="bi bi-plus-circle me-1"></i>
            Pesan Tiket Baru
        </a>
    </div>
</div>

@if ($pemesanans->count() > 0)
    <div class="row g-3">
        @foreach ($pemesanans as $pemesanan)
            @php
                $jadwal = $pemesanan->jadwal;
                $kapal = $jadwal?->kapal;
                $rute = $jadwal?->rute;

                $gambarKapal = collect(
                    $kapal?->gambar_kapal ?? []
                )
                    ->filter()
                    ->values();

                $gambarUtama = $gambarKapal->first();

                $gambarPath = $gambarUtama
                    ? ltrim(
                        preg_replace(
                            '#^(public/|storage/)#',
                            '',
                            $gambarUtama
                        ),
                        '/'
                    )
                    : null;

                $gambarUrl = $gambarPath
                    ? asset('storage/' . $gambarPath)
                    : null;

                $modalId = 'galeri-kapal-' . $pemesanan->id;
            @endphp

            <div class="col-12 col-md-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">

                    @if ($gambarUrl)
                        <button
                            type="button"
                            class="btn p-0 border-0 rounded-0 w-100 text-start"
                            data-bs-toggle="modal"
                            data-bs-target="#{{ $modalId }}"
                            aria-label="Buka galeri {{ $kapal?->nama_kapal ?? 'kapal' }}"
                        >
                            <div class="ratio ratio-16x9 bg-light position-relative">
                                <img
                                    src="{{ $gambarUrl }}"
                                    alt="{{ $kapal?->nama_kapal ?? 'Kapal Ferry' }}"
                                    class="w-100 h-100 object-fit-cover"
                                    loading="lazy"
                                >

                                <div class="position-absolute top-0 start-0 p-2">
                                    @if ($pemesanan->status_pemesanan === 'pending')
                                        <span class="badge text-bg-warning">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Menunggu
                                        </span>
                                    @elseif ($pemesanan->status_pemesanan === 'diterima')
                                        <span class="badge text-bg-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Diterima
                                        </span>
                                    @elseif ($pemesanan->status_pemesanan === 'ditolak')
                                        <span class="badge text-bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary">
                                            {{ ucfirst($pemesanan->status_pemesanan) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="position-absolute bottom-0 start-0 p-2">
                                    <span class="badge text-bg-light">
                                        <i class="bi bi-zoom-in me-1"></i>
                                        Lihat Galeri
                                    </span>
                                </div>

                                <div class="position-absolute bottom-0 end-0 p-2">
                                    <span class="badge text-bg-dark">
                                        <i class="bi bi-images me-1"></i>
                                        {{ $gambarKapal->count() }} Foto
                                    </span>
                                </div>
                            </div>
                        </button>
                    @else
                        <div class="ratio ratio-16x9 bg-light position-relative">
                            <div class="d-flex flex-column align-items-center justify-content-center text-secondary">
                                <i class="bi bi-image fs-1"></i>

                                <small>
                                    Gambar kapal belum tersedia
                                </small>
                            </div>

                            <div class="position-absolute top-0 start-0 p-2">
                                @if ($pemesanan->status_pemesanan === 'pending')
                                    <span class="badge text-bg-warning">
                                        <i class="bi bi-hourglass-split me-1"></i>
                                        Menunggu
                                    </span>
                                @elseif ($pemesanan->status_pemesanan === 'diterima')
                                    <span class="badge text-bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Diterima
                                    </span>
                                @elseif ($pemesanan->status_pemesanan === 'ditolak')
                                    <span class="badge text-bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Ditolak
                                    </span>
                                @else
                                    <span class="badge text-bg-secondary">
                                        {{ ucfirst($pemesanan->status_pemesanan) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column p-3">
                        <small class="text-success fw-semibold mb-1">
                            {{ $pemesanan->kode_pemesanan }}
                        </small>

                        <h5 class="card-title fw-bold text-truncate mb-1">
                            {{ $kapal?->nama_kapal ?? 'Nama Kapal' }}
                        </h5>

                        <p class="text-muted small text-truncate mb-3">
                            <i class="bi bi-geo-alt me-1"></i>

                            {{ $rute?->pelabuhan_asal ?? '-' }}

                            <i class="bi bi-arrow-right mx-1"></i>

                            {{ $rute?->pelabuhan_tujuan ?? '-' }}
                        </p>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="border rounded p-2 h-100">
                                    <small class="text-muted d-block">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        Tanggal
                                    </small>

                                    <span class="small fw-semibold">
                                        {{ optional(
                                            $jadwal?->tanggal_berangkat
                                        )->format('d M Y') ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="border rounded p-2 h-100">
                                    <small class="text-muted d-block">
                                        <i class="bi bi-clock me-1"></i>
                                        Jam
                                    </small>

                                    <span class="small fw-semibold">
                                        {{ $jadwal?->jam_berangkat
                                            ? substr(
                                                (string) $jadwal->jam_berangkat,
                                                0,
                                                5
                                            )
                                            : '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="border rounded p-2">
                                    <small class="text-muted d-block">
                                        <i class="bi bi-ticket-perforated me-1"></i>
                                        Jumlah Tiket
                                    </small>

                                    <span class="small fw-semibold">
                                        {{ $pemesanan->jumlah_tiket }} Tiket
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if ($pemesanan->catatan)
                            <div class="alert alert-light border py-2 px-3 small mb-3">
                                <span class="text-muted d-block">
                                    Catatan
                                </span>

                                {{ \Illuminate\Support\Str::limit(
                                    $pemesanan->catatan,
                                    70
                                ) }}
                            </div>
                        @endif

                        <p class="text-muted small mb-3">
                            <i class="bi bi-clock-history me-1"></i>

                            Dipesan:

                            {{ optional(
                                $pemesanan->waktu_pemesanan
                            )->format('d/m/Y H:i') ?? '-' }}
                        </p>

                        <div class="d-grid d-sm-flex gap-2 mt-auto">
                            @if ($pemesanan->status_pemesanan === 'pending')
                                <a
                                    href="{{ route(
                                        'user.pemesanan.edit',
                                        $pemesanan
                                    ) }}"
                                    class="btn btn-outline-secondary btn-sm"
                                >
                                    <i class="bi bi-pencil me-1"></i>
                                    Edit
                                </a>
                            @endif

                            <a
                                href="{{ route(
                                    'user.pemesanan.show',
                                    $pemesanan
                                ) }}"
                                class="btn btn-success btn-sm ms-sm-auto"
                            >
                                <i class="bi bi-eye me-1"></i>
                                Lihat Detail
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    @foreach ($pemesanans as $pemesanan)
        @include(
            'user.pemesanan.components.modal',
            [
                'pemesanan' => $pemesanan,
            ]
        )
    @endforeach

    <div class="mt-4">
        {{ $pemesanans->links() }}
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 px-3">
            <i class="bi bi-ticket-perforated fs-1 text-success"></i>

            <h5 class="fw-bold mt-3 mb-2">
                Belum Ada Pemesanan
            </h5>

            <p class="text-muted small mb-4">
                Pilih kapal dan jadwal untuk membuat pemesanan pertama.
            </p>

            <a
                href="{{ route('user.pemesanan.create') }}"
                class="btn btn-success"
            >
                <i class="bi bi-search me-1"></i>
                Lihat Kapal Tersedia
            </a>
        </div>
    </div>
@endif

@endsection

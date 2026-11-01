
@extends('layouts.app')

@section('title', 'Detail Peninjauan')
@section('heading', 'Detail Peninjauan Laporan')

@section('content')

@php
    $statusLabels = [
        'menunggu_peninjauan' => 'Menunggu Peninjauan',
        'sedang_ditinjau' => 'Sedang Ditinjau',
        'perlu_tindak_lanjut' => 'Perlu Tindak Lanjut',
        'sedang_ditindaklanjuti' => 'Sedang Ditindaklanjuti',
        'selesai' => 'Selesai',
    ];

    $statusClasses = [
        'menunggu_peninjauan' => 'status-warning',
        'sedang_ditinjau' => 'status-info',
        'perlu_tindak_lanjut' => 'status-danger',
        'sedang_ditindaklanjuti' => 'status-info',
        'selesai' => 'status-success',
    ];
@endphp

<div class="page-header-row">
    <div class="page-header">
        <h1>Detail Peninjauan Laporan</h1>
        <p>Informasi lengkap laporan dan proses peninjauan oleh relawan.</p>
    </div>

    <a href="{{ route('reviews.index') }}" class="btn btn-secondary">
        Kembali ke Daftar
    </a>
</div>

{{-- Informasi utama laporan --}}
<div class="card report-detail-header">
    <div>
        <span class="detail-label">Judul Laporan</span>
        <h2>{{ $report->title }}</h2>
    </div>

    <span class="status-badge {{ $statusClasses[$report->status] ?? '' }}">
        {{ $statusLabels[$report->status] ?? ucfirst($report->status) }}
    </span>
</div>

{{-- Informasi laporan --}}
<div class="card">
    <h3 class="section-title">Informasi Laporan</h3>

    <div class="detail-grid">
        <div class="detail-item">
            <span>Nama Pelapor</span>
            <strong>{{ $report->user->name }}</strong>
        </div>

        <div class="detail-item">
            <span>Wilayah</span>
            <strong>{{ $report->area->name }}</strong>
        </div>

        <div class="detail-item">
            <span>Waktu Kejadian</span>
            <strong>
                {{ \Carbon\Carbon::parse($report->incident_time)->format('d M Y, H:i') }}
            </strong>
        </div>

        <div class="detail-item">
            <span>Tingkat Kondisi</span>
            <strong>{{ ucfirst($report->condition_level) }}</strong>
        </div>

        <div class="detail-item">
            <span>Latitude</span>
            <strong>{{ $report->latitude }}</strong>
        </div>

        <div class="detail-item">
            <span>Longitude</span>
            <strong>{{ $report->longitude }}</strong>
        </div>
    </div>

    <div class="report-description">
        <span class="detail-label">Deskripsi Kejadian</span>
        <p>{{ $report->description }}</p>
    </div>
</div>

{{-- Bukti kejadian --}}
<div class="card">
    <h3 class="section-title">Bukti Kejadian</h3>

    @if ($report->evidence_path)
        <a
            href="{{ asset('storage/' . $report->evidence_path) }}"
            target="_blank"
            rel="noopener noreferrer"
            class="btn btn-secondary"
        >
            Lihat Bukti Kejadian
        </a>
    @else
        <p class="empty-text">Tidak ada bukti yang diunggah.</p>
    @endif
</div>

{{-- Proses peninjauan --}}
<div class="card">
    <h3 class="section-title">Proses Peninjauan</h3>

    @if ($report->status === 'menunggu_peninjauan')

        <p class="form-hint">
            Laporan ini belum ditinjau. Mulai peninjauan untuk memproses laporan.
        </p>

        <form
            action="{{ route('reviews.start', $report->id) }}"
            method="POST"
        >
            @csrf

            <button type="submit" class="btn btn-primary">
                Mulai Peninjauan
            </button>
        </form>

    @elseif ($report->status === 'sedang_ditinjau')

        <p class="form-hint">
            Peninjauan sedang berlangsung. Tambahkan catatan hasil peninjauan sebelum menentukan keputusan.
        </p>

    @elseif ($report->status === 'perlu_tindak_lanjut')

        <p class="form-hint">
            Peninjauan telah selesai dan laporan membutuhkan tindak lanjut dari Koordinator.
        </p>

    @elseif ($report->status === 'sedang_ditindaklanjuti')

        <p class="form-hint">
            Laporan sedang dalam proses tindak lanjut oleh Koordinator.
        </p>

    @elseif ($report->status === 'selesai')

        <p class="form-hint">
            Proses pemantauan laporan telah selesai.
        </p>

    @endif

    {{-- Form catatan --}}
    @if ($report->status === 'sedang_ditinjau')

        <div class="form-divider"></div>

        <h4 class="section-subtitle">Catatan Hasil Peninjauan</h4>

        <form
            action="{{ route('reviews.store', $report->id) }}"
            method="POST"
        >
            @csrf

            <div class="form-group">
                <label for="review_note">Catatan Peninjauan</label>

                <textarea
                    id="review_note"
                    name="review_note"
                    rows="5"
                    required
                    placeholder="Tuliskan hasil pengamatan atau kondisi laporan..."
                >{{ old('review_note') }}</textarea>

                @error('review_note')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan Catatan
            </button>
        </form>
        @include('reports._status-history')

        {{-- Form keputusan --}}
        @if ($report->reviews->isNotEmpty())

            <div class="form-divider"></div>

            <h4 class="section-subtitle">Keputusan Hasil Peninjauan</h4>

            <p class="form-hint">
                Setelah catatan peninjauan disimpan, tentukan status laporan berdasarkan hasil pemeriksaan.
            </p>

            <form
                action="{{ route('reviews.decide', $report->id) }}"
                method="POST"
            >
                @csrf

                <div class="form-group">
                    <label for="status">Pilih Keputusan</label>

                    <select name="status" id="status" required>
                        <option value="">-- Pilih Keputusan --</option>

                        <option value="perlu_tindak_lanjut"
                            @selected(old('status') === 'perlu_tindak_lanjut')>
                            Perlu Tindak Lanjut
                        </option>

                        <option value="selesai"
                            @selected(old('status') === 'selesai')>
                            Selesai
                        </option>
                    </select>

                    @error('status')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Keputusan
                </button>
            </form>

        @endif
    @endif
</div>

{{-- Riwayat peninjauan --}}
<div class="card">
    <h3 class="section-title">Riwayat Peninjauan</h3>

    @forelse ($report->reviews as $review)

        <div class="review-history-item">
            <div class="review-history-header">
                <strong>{{ $review->reviewer->name }}</strong>

                <span>
                    {{ $review->created_at->format('d M Y, H:i') }}
                </span>
            </div>

            <p>{{ $review->review_note }}</p>
        </div>

    @empty

        <p class="empty-text">Belum ada catatan peninjauan.</p>

    @endforelse
</div>
    {{-- RIWAYAT PENERUSAN LAPORAN --}}
    <div class="card">
        <h3>Riwayat Penerusan Laporan</h3>
        <div class="report-list">
            @forelse ($report->followUps as $followUp)
                <div class="report-card">
                    <div class="report-item-header">
                        <h3>{{ $followUp->destination }}</h3>
                        <span class="text-muted">
                            {{ $followUp->created_at->translatedFormat('d F Y, H:i') }}
                        </span>
                    </div>

                    <p>
                        <strong>Diteruskan oleh:</strong>
                        {{ $followUp->coordinator->name }}
                    </p>

                    <p>
                        <strong>Catatan:</strong>
                        {{ $followUp->notes }}
                    </p>
                </div>
            @empty
                <p class="text-muted">
                    Laporan ini belum pernah diteruskan.
                </p>
            @endforelse
        </div>
    </div>
</div>
@endsection

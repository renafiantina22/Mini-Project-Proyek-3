@extends('layouts.app')

@section('title', 'Detail Peninjauan')
@section('heading', 'Peninjauan Laporan')

@section('content')

    @php
        $statuses = [
            'menunggu_peninjauan'    => ['Menunggu Peninjauan',    'status-waiting'],
            'sedang_ditinjau'        => ['Sedang Ditinjau',        'status-review'],
            'perlu_tindak_lanjut'    => ['Perlu Tindak Lanjut',    'status-follow-up'],
            'sedang_ditindaklanjuti' => ['Sedang Ditindaklanjuti', 'status-progress'],
            'selesai'                => ['Selesai',                'status-completed'],
        ];
        [$statusLabel, $statusClass] = $statuses[$report->status]
            ?? [ucwords(str_replace('_', ' ', $report->status)), ''];
        $isImage = $report->evidence_path
            && preg_match('/\.(jpe?g|png|webp|gif)$/i', $report->evidence_path);
    @endphp

    <div class="page-header-row">
        <div class="page-header">
            <h1>Detail Peninjauan Laporan</h1>
            <p>Tinjau laporan, catat hasil peninjauan, lalu tentukan keputusan.</p>
        </div>

        <a href="{{ route('reviews.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- INFORMASI LAPORAN --}}
    <div class="card">
        <div class="report-item-header">
            <h3 style="font-size:18px">{{ $report->title }}</h3>
            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>

        <p>{{ $report->description }}</p>
    </div>

    <div class="card">
        <h3>Informasi Kejadian</h3>

        <p><strong>Pelapor:</strong> {{ $report->user->name }}</p>
        <p><strong>Wilayah:</strong> {{ $report->area->name }}</p>
        <p><strong>Waktu Kejadian:</strong> {{ \Carbon\Carbon::parse($report->incident_time)->translatedFormat('d F Y, H:i') }}</p>
        <p><strong>Tingkat Kondisi:</strong> {{ ucfirst($report->condition_level) }}</p>
        <p><strong>Koordinat:</strong> {{ $report->latitude }}, {{ $report->longitude }}</p>
    </div>

    <div class="card">
        <h3>Bukti Kejadian</h3>

        @if ($report->evidence_path)
            @if ($isImage)
                <p>
                    <img src="{{ asset('storage/' . $report->evidence_path) }}"
                         alt="Bukti kejadian"
                         style="max-width:100%; max-height:360px; border-radius:8px; border:1px solid var(--border);">
                </p>
            @endif

            <a href="{{ asset('storage/' . $report->evidence_path) }}" target="_blank" class="btn btn-secondary">
                Lihat Bukti Kejadian
            </a>
        @else
            <p class="text-muted">Tidak ada bukti yang diunggah.</p>
        @endif
    </div>

    {{-- RIWAYAT PENINJAUAN --}}
    <div class="card">
        <h3>Riwayat Peninjauan</h3>

        <div class="report-list">
            @forelse ($report->reviews as $review)
                <div class="report-card">
                    <div class="report-item-header">
                        <h3>{{ $review->reviewer->name }}</h3>
                        <span class="text-muted">{{ $review->created_at->translatedFormat('d F Y, H:i') }}</span>
                    </div>
                    <p>{{ $review->review_note }}</p>
                </div>
            @empty
                <p class="text-muted">Belum ada catatan peninjauan.</p>
            @endforelse
        </div>
    </div>

    {{-- MULAI PENINJAUAN --}}
    @if ($report->status === 'menunggu_peninjauan')
        <div class="card">
            <h3>Mulai Peninjauan</h3>
            <p>Laporan ini belum ditinjau. Mulai peninjauan untuk dapat menambahkan catatan.</p>

            <form action="{{ route('reviews.start', $report->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Mulai Peninjauan</button>
            </form>
        </div>
    @endif

    {{-- CATATAN PENINJAUAN --}}
    @if ($report->status === 'sedang_ditinjau')
        <div class="card">
            <h3>Catatan Hasil Peninjauan</h3>

            <form action="{{ route('reviews.store', $report->id) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="review_note">Catatan Peninjauan</label>
                    <textarea id="review_note" name="review_note" rows="5" required>{{ old('review_note') }}</textarea>

                    @error('review_note')
                        <small style="color: var(--danger)">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Catatan</button>
            </form>
        </div>
    @endif

    {{-- KEPUTUSAN --}}
    @if ($report->status === 'sedang_ditinjau' && $report->reviews->isNotEmpty())
        <div class="card">
            <h3>Keputusan Hasil Peninjauan</h3>

            <form action="{{ route('reviews.decide', $report->id) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="status">Pilih Keputusan</label>
                    <select name="status" id="status" required>
                        <option value="">-- Pilih Keputusan --</option>
                        <option value="perlu_tindak_lanjut">Perlu Tindak Lanjut</option>
                        <option value="selesai">Selesai</option>
                    </select>

                    @error('status')
                        <small style="color: var(--danger)">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
            </form>
        </div>
    @endif

@endsection
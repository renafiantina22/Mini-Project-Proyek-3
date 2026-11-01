@extends('layouts.app')

@section('title', 'Detail Tindak Lanjut')
@section('heading', 'Tindak Lanjut')

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
            <h1>Detail Tindak Lanjut Laporan</h1>
            <p>Teruskan laporan ke pihak terkait dan pantau prosesnya sampai selesai.</p>
        </div>

        <a href="{{ route('follow-ups.index') }}" class="btn btn-secondary">← Kembali</a>
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
        @endif
    </div>

    {{-- CATATAN PENINJAUAN RELAWAN --}}
    <div class="card">
        <h3>Catatan Peninjauan Relawan</h3>

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

    {{-- RIWAYAT TINDAK LANJUT --}}
    <div class="card">
        <h3>Riwayat Tindak Lanjut</h3>

        <div class="report-list">
            @forelse ($report->followUps as $followUp)
                <div class="report-card">
                    <div class="report-item-header">
                        <h3>{{ $followUp->destination }}</h3>
                        <span class="text-muted">{{ \Carbon\Carbon::parse($followUp->forwarded_at)->translatedFormat('d F Y, H:i') }}</span>
                    </div>
                    <p>{{ $followUp->notes }}</p>
                    <p class="text-muted">Diteruskan oleh {{ $followUp->coordinator->name }}</p>
                </div>
            @empty
                <p class="text-muted">Belum ada riwayat tindak lanjut.</p>
            @endforelse
        </div>
    </div>
    
    @include('reports._status-history')

    {{-- FORM PENYELESAIAN --}}
    @if ($report->status === 'sedang_ditindaklanjuti')
        <div class="card">
            <h3>Selesaikan Tindak Lanjut</h3>
            <p>Pastikan seluruh proses penerusan dan tindak lanjut sudah dilakukan sebelum menyelesaikan laporan.</p>

            <form action="{{ route('follow-ups.complete', $report->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Tandai Sebagai Selesai</button>
            </form>
        </div>
    @endif

    {{-- FORM PENERUSAN --}}
    @if (in_array($report->status, ['perlu_tindak_lanjut', 'sedang_ditindaklanjuti']))
        <div class="card">
            <h3>Teruskan Laporan</h3>

            <form action="{{ route('follow-ups.forward', $report->id) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="destination">Tujuan Penerusan</label>
                    <select name="destination" id="destination" required>
                        <option value="">-- Pilih Tujuan --</option>
                        @foreach (['BPBD', 'Damkar', 'Pemerintah Desa', 'Pengelola Wilayah'] as $destination)
                            <option value="{{ $destination }}" {{ old('destination') == $destination ? 'selected' : '' }}>
                                {{ $destination }}
                            </option>
                        @endforeach
                    </select>

                    @error('destination')
                        <small style="color: var(--danger)">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="notes">Catatan Penerusan</label>
                    <textarea name="notes" id="notes" required>{{ old('notes') }}</textarea>

                    @error('notes')
                        <small style="color: var(--danger)">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Teruskan Laporan</button>
            </form>
        </div>
    @endif
@endsection
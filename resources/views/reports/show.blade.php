@extends('layouts.app')

@section('title', 'Detail Laporan')
@section('heading', 'Detail Laporan')

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
            <h1>Detail Laporan Karhutla</h1>
            <p>Informasi lengkap laporan kejadian kebakaran hutan dan lahan.</p>
        </div>

        <div class="form-actions" style="margin-top:0">
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">← Kembali</a>

            @if (
                auth()->user()->role === 'pelapor' &&
                $report->user_id === auth()->id() &&
                $report->status === 'menunggu_peninjauan'
            )
                <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-primary">Edit Laporan</a>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="report-item-header">
            <h3 style="font-size:18px">{{ $report->title }}</h3>
            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>

        <p>{{ $report->description }}</p>
    </div>

    <div class="card">
        <h3>Informasi Kejadian</h3>

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

@endsection
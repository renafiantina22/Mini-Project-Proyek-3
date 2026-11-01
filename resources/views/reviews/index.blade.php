@extends('layouts.app')

@section('title', 'Daftar Peninjauan')
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
    @endphp

    <div class="page-header">
        <h1>Daftar Laporan untuk Ditinjau</h1>
        <p>Berikut adalah seluruh laporan yang masuk ke sistem dan perlu ditinjau oleh relawan.</p>
    </div>

    <div class="report-list">
        @forelse ($reports as $report)

            @php
                [$statusLabel, $statusClass] = $statuses[$report->status]
                    ?? [ucwords(str_replace('_', ' ', $report->status)), ''];
            @endphp

            <article class="report-card">

                <div class="report-item-header">
                    <h3>{{ $report->title }}</h3>
                    <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>

                <div class="report-meta">
                    <p>Dilaporkan oleh {{ $report->user->name }}</p>
                    <p>Wilayah: {{ $report->area->name }}</p>
                    <p>Waktu kejadian: {{ \Carbon\Carbon::parse($report->incident_time)->translatedFormat('d F Y') }}</p>
                    <p>Tingkat kondisi: {{ ucfirst($report->condition_level) }}</p>
                </div>

                <div class="report-actions">
                    <a href="{{ route('reviews.show', $report->id) }}" class="btn btn-secondary">
                        Lihat Detail Peninjauan
                    </a>
                </div>

            </article>

        @empty
            <div class="card empty-state">
                <h3>Belum Ada Laporan</h3>
                <p>Belum ada laporan yang tersedia untuk ditinjau.</p>
            </div>
        @endforelse
    </div>

    {{ $reports->links() }}

@endsection
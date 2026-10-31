<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan</title>
</head>
<body>
    <h1>Detail Laporan Karhutla</h1>

    <h2>{{ $report->title }}</h2>

    <p><strong>Deskripsi:</strong> {{ $report->description }}</p>
    <p><strong>Wilayah:</strong> {{ $report->area->name }}</p>
    <p><strong>Waktu:</strong> {{ $report->incident_time }}</p>
    <p><strong>Latitude:</strong> {{ $report->latitude }}</p>
    <p><strong>Longitude:</strong> {{ $report->longitude }}</p>
    <p><strong>Tingkat Kondisi:</strong> {{ ucfirst($report->condition_level) }}</p>
    <p><strong>Status:</strong> {{ $report->status }}</p>

    <a href="{{ route('reports.index') }}">Kembali ke Daftar Laporan</a>

    @if (
        auth()->user()->role === 'pelapor' &&
        $report->user_id === auth()->id() &&
        $report->status === 'menunggu_peninjauan'
    )
        <a href="{{ route('reports.edit', $report->id) }}">
            Edit Laporan
        </a>
    @endif

    @if ($report->evidence_path)
        <p><strong>Bukti Kejadian:</strong></p>

        <a
            href="{{ asset('storage/' . $report->evidence_path) }}"
            target="_blank"
        >
            Lihat Bukti Kejadian
        </a>
    @else
        <p><strong>Bukti Kejadian:</strong> Tidak ada bukti.</p>
    @endif

</body>
</html>
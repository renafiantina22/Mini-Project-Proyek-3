<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laporan Karhutla</title>
</head>
<body>
    <h1>Daftar Laporan Karhutla</h1>

    <p>Berikut adalah laporan yang telah kamu buat.</p>

    @if (session('success'))
        <div>
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('reports.create') }}">Buat Laporan Baru</a>
    <a href="{{ route('dashboard') }}">Kembali ke Dashboard</a>

    <hr>

    @forelse ($reports as $report)
        <div>
            <h3>{{ $report->title }}</h3>

            <p>
                <strong>Wilayah:</strong>
                {{ $report->area->name }}
            </p>

            <p>
                <strong>Waktu Kejadian:</strong>
                {{ $report->incident_time }}
            </p>

            <p>
                <strong>Tingkat Kondisi:</strong>
                {{ ucfirst($report->condition_level) }}
            </p>

            <p>
                <strong>Status:</strong>
                {{ $report->status }}
            </p>

            <a href="{{ route('reports.show', $report->id) }}">
                Lihat Detail
            </a>
        </div>

        <hr>
    @empty
        <p>Belum ada laporan yang dibuat.</p>
    @endforelse

    {{ $reports->links() }}
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peninjauan Laporan</title>
</head>
<body>
    <h1>Daftar Laporan untuk Ditinjau</h1>

    <p>Berikut adalah seluruh laporan yang masuk ke sistem.</p>

    <a href="{{ route('dashboard') }}">Kembali ke Dashboard</a>

    <hr>

    @forelse ($reports as $report)
        <div>
            <h3>{{ $report->title }}</h3>

            <p>
                <strong>Pelapor:</strong>
                {{ $report->user->name }}
            </p>

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
                {{ ucwords(str_replace('_', ' ', $report->status)) }}
            </p>

            <a href="{{ route('reviews.show', $report->id) }}">
                Lihat Detail Peninjauan
            </a>
        </div>

        <hr>
    @empty
        <p>Belum ada laporan yang tersedia.</p>
    @endforelse

    {{ $reports->links() }}
</body>
</html>

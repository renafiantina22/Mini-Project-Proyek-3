
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peninjauan Laporan</title>
</head>
<body>
    <h1>Detail Peninjauan Laporan</h1>

    <a href="{{ route('reviews.index') }}">
        Kembali ke Daftar Laporan
    </a>

    <hr>

    <h2>{{ $report->title }}</h2>

    <p>
        <strong>Pelapor:</strong>
        {{ $report->user->name }}
    </p>

    <p>
        <strong>Wilayah:</strong>
        {{ $report->area->name }}
    </p>

    <p>
        <strong>Deskripsi:</strong>
        {{ $report->description }}
    </p>

    <p>
        <strong>Waktu Kejadian:</strong>
        {{ \Carbon\Carbon::parse($report->incident_time)->format('d-m-Y H:i') }}
    </p>

    <p>
        <strong>Latitude:</strong>
        {{ $report->latitude }}
    </p>

    <p>
        <strong>Longitude:</strong>
        {{ $report->longitude }}
    </p>

    <p>
        <strong>Tingkat Kondisi:</strong>
        {{ ucfirst($report->condition_level) }}
    </p>

    <p>
        <strong>Status:</strong>
        {{ ucwords(str_replace('_', ' ', $report->status)) }}
    </p>

    <h3>Bukti Kejadian</h3>

    @if ($report->evidence_path)
        <a
            href="{{ asset('storage/' . $report->evidence_path) }}"
            target="_blank"
        >
            Lihat Bukti Kejadian
        </a>
    @else
        <p>Tidak ada bukti yang diunggah.</p>
    @endif

    @if (
    $report->status === 'sedang_ditinjau'
    && $report->reviews->isNotEmpty()
)
    <hr>

    <h3>Keputusan Hasil Peninjauan</h3>
        <form
            action="{{ route('reviews.decide', $report->id) }}"
            method="POST"
        >
            @csrf

            <label for="status">Pilih Keputusan:</label>

            <br>

            <select name="status" id="status" required>
                <option value="">-- Pilih Keputusan --</option>

                <option value="perlu_tindak_lanjut">
                    Perlu Tindak Lanjut
                </option>

                <option value="selesai">
                    Selesai
                </option>
            </select>

            @error('status')
                <p>{{ $message }}</p>
            @enderror

            <br><br>

            <button type="submit">
                Simpan Keputusan
            </button>
        </form>
    @endif

    <hr>

    <h3>Riwayat Peninjauan</h3>

    @forelse ($report->reviews as $review)
        <div>
            <p>
                <strong>Relawan:</strong>
                {{ $review->reviewer->name }}
            </p>

            <p>
                <strong>Catatan:</strong>
                {{ $review->review_note }}
            </p>

            <p>
                <strong>Waktu:</strong>
                {{ $review->created_at->format('d-m-Y H:i') }}
            </p>
        </div>
        <hr>
    @empty
        <p>Belum ada catatan peninjauan.</p>
    @endforelse


    @if ($report->status === 'menunggu_peninjauan')
        <form
            action="{{ route('reviews.start', $report->id) }}"
            method="POST"
        >
            @csrf

            <button type="submit">
                Mulai Peninjauan
            </button>
        </form>

    @elseif ($report->status === 'sedang_ditinjau')
        <p>Peninjauan sedang berlangsung.</p>
    @endif

    @if ($report->status === 'sedang_ditinjau')
        <hr>

        <h3>Catatan Hasil Peninjauan</h3>

        <form
            action="{{ route('reviews.store', $report->id) }}"
            method="POST"
        >
            @csrf

            <div>
                <label for="review_note">
                    Catatan Peninjauan
                </label>

                <br>

                <textarea
                    id="review_note"
                    name="review_note"
                    rows="5"
                    required
                >{{ old('review_note') }}</textarea>
            </div>

            @error('review_note')
                <p>{{ $message }}</p>
            @enderror

            <br>

            <button type="submit">
                Simpan Catatan
            </button>
        </form>
    @endif
</body>
</html>

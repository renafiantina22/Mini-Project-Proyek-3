<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Tindak Lanjut</title>
</head>
<body>

    {{-- Notifikasi keberhasilan --}}
    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    <h1>Detail Tindak Lanjut Laporan</h1>

    <a href="{{ route('follow-ups.index') }}">
        Kembali ke Daftar Tindak Lanjut
    </a>

    <hr>

    {{-- INFORMASI LAPORAN --}}
    <h2>Informasi Laporan</h2>

    <p><strong>Judul:</strong> {{ $report->title }}</p>
    <p><strong>Pelapor:</strong> {{ $report->user->name }}</p>
    <p><strong>Wilayah:</strong> {{ $report->area->name }}</p>
    <p><strong>Deskripsi:</strong> {{ $report->description }}</p>
    <p><strong>Waktu Kejadian:</strong> {{ $report->incident_time }}</p>
    <p><strong>Latitude:</strong> {{ $report->latitude }}</p>
    <p><strong>Longitude:</strong> {{ $report->longitude }}</p>
    <p><strong>Tingkat Kondisi:</strong> {{ ucfirst($report->condition_level) }}</p>
    <p><strong>Status:</strong> {{ ucwords(str_replace('_', ' ', $report->status)) }}</p>

    @if ($report->evidence_path)
        <p>
            <strong>Bukti:</strong>
            <a href="{{ asset('storage/' . $report->evidence_path) }}" target="_blank">
                Lihat Bukti
            </a>
        </p>
    @endif

    <hr>

    {{-- CATATAN PENINJAUAN RELAWAN --}}
    <h2>Catatan Peninjauan Relawan</h2>

    @forelse ($report->reviews as $review)
        <div>
            <p><strong>Relawan:</strong> {{ $review->reviewer->name }}</p>
            <p><strong>Catatan:</strong> {{ $review->review_note }}</p>
            <p><strong>Waktu:</strong> {{ $review->created_at }}</p>
        </div>
        <hr>
    @empty
        <p>Belum ada catatan peninjauan.</p>
    @endforelse

    {{-- RIWAYAT TINDAK LANJUT --}}
    <h2>Riwayat Tindak Lanjut</h2>

    @forelse ($report->followUps as $followUp)
        <div>
            <p><strong>Koordinator:</strong> {{ $followUp->coordinator->name }}</p>
            <p><strong>Tujuan Penerusan:</strong> {{ $followUp->destination }}</p>
            <p><strong>Catatan:</strong> {{ $followUp->notes }}</p>
            <p><strong>Waktu Penerusan:</strong> {{ $followUp->forwarded_at }}</p>
        </div>
        <hr>
    @empty
        <p>Belum ada riwayat tindak lanjut.</p>
    @endforelse

    {{-- FORM PENYELESAIAN --}}
    @if ($report->status === 'sedang_ditindaklanjuti')
        <hr>

        <h2>Selesaikan Tindak Lanjut</h2>

        <p>
            Pastikan seluruh proses penerusan dan tindak lanjut
            sudah dilakukan sebelum menyelesaikan laporan.
        </p>

        <form action="{{ route('follow-ups.complete', $report->id) }}" method="POST">
            @csrf

            <button type="submit">
                Tandai Sebagai Selesai
            </button>
        </form>
    @endif

    {{-- FORM PENERUSAN --}}
    @if (in_array($report->status, [
        'perlu_tindak_lanjut',
        'sedang_ditindaklanjuti'
    ]))

        <hr>

        <h2>Teruskan Laporan</h2>

        <form action="{{ route('follow-ups.forward', $report->id) }}" method="POST">
            @csrf

            <div>
                <label for="destination">Tujuan Penerusan:</label>

                <select name="destination" id="destination" required>
                    <option value="">-- Pilih Tujuan --</option>

                    <option value="BPBD" {{ old('destination') == 'BPBD' ? 'selected' : '' }}>
                        BPBD
                    </option>

                    <option value="Damkar" {{ old('destination') == 'Damkar' ? 'selected' : '' }}>
                        Damkar
                    </option>

                    <option value="Pemerintah Desa" {{ old('destination') == 'Pemerintah Desa' ? 'selected' : '' }}>
                        Pemerintah Desa
                    </option>

                    <option value="Pengelola Wilayah" {{ old('destination') == 'Pengelola Wilayah' ? 'selected' : '' }}>
                        Pengelola Wilayah
                    </option>
                </select>

                @error('destination')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>

            <br>

            <div>
                <label for="notes">Catatan Penerusan:</label>

                <textarea name="notes" id="notes" required>{{ old('notes') }}</textarea>

                @error('notes')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
            </div>

            <br>

            <button type="submit">
                Teruskan Laporan
            </button>
        </form>
    @endif

</body>
</html>

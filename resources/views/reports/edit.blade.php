
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laporan Karhutla</title>
</head>
<body>
    <h1>Edit Laporan Karhutla</h1>

    <p>Perbarui informasi laporan kebakaran hutan dan lahan.</p>

    <a href="{{ route('reports.show', $report->id) }}">
        Kembali ke Detail
    </a>

    @if ($errors->any())
        <div>
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reports.update', $report->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div>
            <label for="title">Judul Laporan</label><br>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $report->title) }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="description">Deskripsi Kejadian</label><br>
            <textarea
                id="description"
                name="description"
                required
            >{{ old('description', $report->description) }}</textarea>
        </div>

        <br>

        <div>
            <label for="area_id">Wilayah Kejadian</label><br>
            <select id="area_id" name="area_id" required>
                <option value="">-- Pilih Wilayah --</option>

                @foreach ($areas as $area)
                    <option
                        value="{{ $area->id }}"
                        {{ old('area_id', $report->area_id) == $area->id ? 'selected' : '' }}
                    >
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <div>
            <label for="incident_time">Waktu Kejadian</label><br>
            <input
                type="datetime-local"
                id="incident_time"
                name="incident_time"
                value="{{ old('incident_time', \Carbon\Carbon::parse($report->incident_time)->format('Y-m-d\TH:i')) }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="latitude">Latitude</label><br>
            <input
                type="number"
                step="any"
                id="latitude"
                name="latitude"
                value="{{ old('latitude', $report->latitude) }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="longitude">Longitude</label><br>
            <input
                type="number"
                step="any"
                id="longitude"
                name="longitude"
                value="{{ old('longitude', $report->longitude) }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="condition_level">Tingkat Kondisi</label><br>
            <select id="condition_level" name="condition_level" required>
                <option value="">-- Pilih Tingkat Kondisi --</option>
                <option value="rendah" {{ old('condition_level', $report->condition_level) == 'rendah' ? 'selected' : '' }}>
                    Rendah
                </option>
                <option value="sedang" {{ old('condition_level', $report->condition_level) == 'sedang' ? 'selected' : '' }}>
                    Sedang
                </option>
                <option value="tinggi" {{ old('condition_level', $report->condition_level) == 'tinggi' ? 'selected' : '' }}>
                    Tinggi
                </option>
            </select>
        </div>

        <br>

        <div>
            <label for="evidence">Bukti Kejadian (Opsional)</label><br>

            @if ($report->evidence_path)
                <p>
                    Bukti saat ini:
                    <a href="{{ asset('storage/' . $report->evidence_path) }}" target="_blank">
                        Lihat Bukti
                    </a>
                </p>
            @else
                <p>Belum ada bukti yang diunggah.</p>
            @endif

            <input
                type="file"
                id="evidence"
                name="evidence"
                accept=".jpg,.jpeg,.png,.pdf"
            >

            <small>
                Kosongkan jika tidak ingin mengganti bukti.
                Format JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.
            </small>
        </div>

        <br>

        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>

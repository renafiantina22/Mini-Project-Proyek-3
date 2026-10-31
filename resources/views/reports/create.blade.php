<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan Karhutla</title>
</head>
<body>
    <h1>Formulir Pelaporan Karhutla</h1>
    <p>Silakan isi informasi kejadian dengan lengkap dan benar.</p>

    @if ($errors->any())
        <div>
            <strong>Periksa kembali data yang dimasukkan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reports.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div>
            <label for="title">Judul Laporan</label>
            <input type="text" id="title" name="title"
                   value="{{ old('title') }}" required>
        </div>

        <div>
            <label for="description">Deskripsi Kejadian</label>
            <textarea id="description" name="description"
                      required>{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="area_id">Wilayah Kejadian</label>
            <select id="area_id" name="area_id" required>
                <option value="">Pilih Wilayah</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}"
                        @selected(old('area_id') == $area->id)>
                        {{ $area->name }} -
                        {{ $area->administrative_region }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="incident_time">Waktu Kejadian</label>
            <input type="datetime-local" id="incident_time"
                   name="incident_time"
                   value="{{ old('incident_time') }}" required>
        </div>

        <div>
            <label for="latitude">Latitude</label>
            <input type="number" step="any" id="latitude"
                   name="latitude" value="{{ old('latitude') }}"
                   min="-90" max="90" required>
        </div>

        <div>
            <label for="longitude">Longitude</label>
            <input type="number" step="any" id="longitude"
                   name="longitude" value="{{ old('longitude') }}"
                   min="-180" max="180" required>
        </div>

        <div>
            <label for="condition_level">Tingkat Kondisi</label>
            <select id="condition_level" name="condition_level" required>
                <option value="">Pilih Tingkat Kondisi</option>
                <option value="rendah"
                    @selected(old('condition_level') == 'rendah')>
                    Rendah
                </option>
                <option value="sedang"
                    @selected(old('condition_level') == 'sedang')>
                    Sedang
                </option>
                <option value="tinggi"
                    @selected(old('condition_level') == 'tinggi')>
                    Tinggi
                </option>
            </select>
        </div>

        <div>
            <label for="evidence">Bukti Kejadian (Opsional)</label>
            <input type="file" id="evidence" name="evidence"
                   accept=".jpg,.jpeg,.png,.pdf">
            <small>Format JPG, JPEG, PNG, PDF. Maksimal 2 MB.</small>
        </div>

        <button type="submit">Kirim Laporan</button>
        <a href="{{ route('dashboard') }}">Kembali</a>
    </form>
</body>
</html>
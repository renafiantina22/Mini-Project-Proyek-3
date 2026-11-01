@extends('layouts.app')

@section('title', 'Buat Laporan')
@section('heading', 'Buat Laporan')

@section('content')

    <div class="page-header-row">
        <div class="page-header">
            <h1>Formulir Pelaporan Karhutla</h1>
            <p>
                Silakan isi informasi kejadian dengan lengkap dan benar.
            </p>
        </div>

        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="card report-form-card">

        <div class="form-section-heading">
            <h2>Informasi Kejadian</h2>
            <p class="text-muted">
                Lengkapi informasi berikut untuk membuat laporan baru.
            </p>
        </div>

        {{-- PESAN VALIDASI --}}
        @if ($errors->any())
            <div class="alert alert-error">
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

            <div class="form-group">
                <label for="title">Judul Laporan</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="Contoh: Asap Terlihat di Area Perbukitan"
                    maxlength="255"
                    required
                >
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Kejadian</label>
                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Jelaskan kondisi dan kronologi kejadian..."
                    required
                >{{ old('description') }}</textarea>
            </div>

            <div class="form-grid">
                <div class="form-group">
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

                <div class="form-group">
                    <label for="incident_time">Waktu Kejadian</label>
                    <input
                        type="datetime-local"
                        id="incident_time"
                        name="incident_time"
                        value="{{ old('incident_time') }}"
                        required
                    >
                </div>
            </div>

            <div class="form-section-heading coordinate-heading">
                <h2>Lokasi Kejadian</h2>
                <p class="text-muted">
                    Masukkan koordinat lokasi kejadian.
                </p>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="latitude">Latitude</label>
                    <input
                        type="number"
                        step="any"
                        id="latitude"
                        name="latitude"
                        value="{{ old('latitude') }}"
                        min="-90"
                        max="90"
                        placeholder="Contoh: -6.8200"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input
                        type="number"
                        step="any"
                        id="longitude"
                        name="longitude"
                        value="{{ old('longitude') }}"
                        min="-180"
                        max="180"
                        placeholder="Contoh: 107.5800"
                        required
                    >
                </div>
            </div>

            <div class="form-section-heading coordinate-heading">
                <h2>Kondisi Kejadian</h2>
            </div>

            <div class="form-group">
                <label for="condition_level">Tingkat Kondisi</label>
                <select id="condition_level"
                        name="condition_level"
                        required>

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

            <div class="form-section-heading coordinate-heading">
                <h2>Bukti Kejadian</h2>
                <p class="text-muted">
                    Unggah foto atau dokumen pendukung jika tersedia.
                </p>
            </div>

            <div class="form-group">
                <label for="evidence">Bukti Kejadian (Opsional)</label>
                <input
                    type="file"
                    id="evidence"
                    name="evidence"
                    accept=".jpg,.jpeg,.png,.pdf"
                >
                <small class="form-hint">
                    Format JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.
                </small>
            </div>

            <div class="form-actions">
                <a href="{{ route('reports.index') }}"
                   class="btn btn-secondary">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
@endsection

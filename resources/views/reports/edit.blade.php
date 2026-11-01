@extends('layouts.app')

@section('title', 'Edit Laporan')
@section('heading', 'Edit Laporan')

@section('content')

    <div class="page-header-row">
        <div class="page-header">
            <h1>Edit Laporan Karhutla</h1>
            <p>Perbarui informasi laporan kebakaran hutan dan lahan.</p>
        </div>

        <a href="{{ route('reports.show', $report->id) }}"
           class="btn btn-secondary">
            Kembali ke Detail
        </a>
    </div>

    <div class="card report-form-card">

        <div class="form-section-heading">
            <h2>Informasi Kejadian</h2>
            <p class="text-muted">
                Perbarui informasi kejadian yang telah dilaporkan.
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

        <form action="{{ route('reports.update', $report->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Judul Laporan</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $report->title) }}"
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
                    required
                >{{ old('description', $report->description) }}</textarea>
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label for="area_id">Wilayah Kejadian</label>

                    <select id="area_id" name="area_id" required>
                        <option value="">Pilih Wilayah</option>

                        @foreach ($areas as $area)
                            <option
                                value="{{ $area->id }}"
                                @selected(old('area_id', $report->area_id) == $area->id)
                            >
                                {{ $area->name }}
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
                        value="{{ old('incident_time', \Carbon\Carbon::parse($report->incident_time)->format('Y-m-d\TH:i')) }}"
                        required
                    >
                </div>

            </div>

            <div class="form-section-heading coordinate-heading">
                <h2>Lokasi Kejadian</h2>
                <p class="text-muted">
                    Perbarui koordinat lokasi kejadian jika diperlukan.
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
                        value="{{ old('latitude', $report->latitude) }}"
                        min="-90"
                        max="90"
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
                        value="{{ old('longitude', $report->longitude) }}"
                        min="-180"
                        max="180"
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
                        @selected(old('condition_level', $report->condition_level) == 'rendah')>
                        Rendah
                    </option>

                    <option value="sedang"
                        @selected(old('condition_level', $report->condition_level) == 'sedang')>
                        Sedang
                    </option>

                    <option value="tinggi"
                        @selected(old('condition_level', $report->condition_level) == 'tinggi')>
                        Tinggi
                    </option>

                </select>
            </div>

            <div class="form-section-heading coordinate-heading">
                <h2>Bukti Kejadian</h2>
                <p class="text-muted">
                    Bukti yang sudah diunggah dapat dipertahankan atau diganti.
                </p>
            </div>

            <div class="form-group">
                <label for="evidence">Bukti Kejadian (Opsional)</label>

                @if ($report->evidence_path)
                    <div class="current-evidence">
                        <span>Bukti saat ini sudah tersedia.</span>

                        <a href="{{ asset('storage/' . $report->evidence_path) }}"
                           target="_blank"
                           rel="noopener noreferrer">
                            Lihat Bukti
                        </a>
                    </div>
                @else
                    <p class="text-muted">
                        Belum ada bukti yang diunggah.
                    </p>
                @endif

                <input
                    type="file"
                    id="evidence"
                    name="evidence"
                    accept=".jpg,.jpeg,.png,.pdf"
                >

                <small class="form-hint">
                    Kosongkan jika tidak ingin mengganti bukti.
                    Format JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.
                </small>
            </div>

            <div class="form-actions">

                <a href="{{ route('reports.show', $report->id) }}"
                   class="btn btn-secondary">
                    Batal
                </a>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

@endsection

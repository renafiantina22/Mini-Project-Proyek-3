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

            {{-- INFORMASI WILAYAH DARI FETCH --}}
            <div id="area-information"
                 class="card"
                 style="display: none; margin-bottom: 20px;">

                <h3>Informasi Wilayah Terpilih</h3>

                <p>
                    <strong>Wilayah:</strong>
                    <span id="area-name">-</span>
                </p>

                <p>
                    <strong>Jumlah Laporan Aktif:</strong>
                    <span id="active-reports">0</span>
                </p>

                <p>
                    <strong>Jumlah Laporan Membutuhkan Tindak Lanjut:</strong>
                    <span id="follow-up-reports">0</span>
                </p>
            </div>

            <p id="area-loading"
               class="text-muted"
               style="display: none;">
                Mengambil informasi wilayah...
            </p>

            <p id="area-error"
               style="display: none; color: var(--danger);">
                Gagal mengambil informasi wilayah. Silakan coba kembali.
            </p>

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

{{-- JAVASCRIPT FETCH INFORMASI WILAYAH --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const areaSelect = document.getElementById('area_id');

    const information = document.getElementById('area-information');
    const areaName = document.getElementById('area-name');
    const activeReports = document.getElementById('active-reports');
    const followUpReports = document.getElementById('follow-up-reports');

    const loading = document.getElementById('area-loading');
    const error = document.getElementById('area-error');

    const urlTemplate = @json(route('areas.information', ['area' => '__AREA__']));

    areaSelect.addEventListener('change', async function () {

        const areaId = this.value;

        information.style.display = 'none';
        error.style.display = 'none';

        if (!areaId) {
            loading.style.display = 'none';
            return;
        }

        loading.style.display = 'block';

        try {

            const url = urlTemplate.replace('__AREA__', areaId);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil informasi wilayah.');
            }

            const data = await response.json();

            areaName.textContent = data.area;
            activeReports.textContent = data.active_reports;
            followUpReports.textContent = data.follow_up_reports;

            information.style.display = 'block';

        } catch (err) {

            console.error(err);
            error.style.display = 'block';

        } finally {

            loading.style.display = 'none';

        }
    });

    // Memuat informasi jika wilayah sudah terpilih sebelumnya.
    if (areaSelect.value) {
        areaSelect.dispatchEvent(new Event('change'));
    }

});
</script>
@endpush

@extends('layouts.app')

@section('title', 'Daftar Laporan')
@section('heading', 'Daftar Laporan')

@section('content')

    {{-- HEADER HALAMAN --}}
    <div class="page-header-row">
        <div class="page-header">
            <h1>Daftar Laporan Karhutla</h1>
            <p>Kelola dan pantau laporan kejadian kebakaran hutan dan lahan.</p>
        </div>

        <a href="{{ route('reports.create') }}" class="btn btn-primary">
            + Buat Laporan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- DAFTAR LAPORAN --}}
    <div class="report-list">

        @forelse ($reports as $report)

            @php
                $statusClasses = [
                    'menunggu_peninjauan' => 'status-waiting',
                    'sedang_ditinjau' => 'status-review',
                    'perlu_tindak_lanjut' => 'status-follow-up',
                    'sedang_ditindaklanjuti' => 'status-progress',
                    'selesai' => 'status-completed',
                ];
            @endphp

            <div class="report-card">

                <div class="report-item-header">
                    <h3>{{ $report->title }}</h3>

                    <span class="status-badge {{ $statusClasses[$report->status] ?? '' }}">
                        {{ ucwords(str_replace('_', ' ', $report->status)) }}
                    </span>
                </div>

                <div class="report-meta">
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
                </div>

                <div class="report-actions">
                    <a href="{{ route('reports.show', $report->id) }}"
                       class="btn btn-secondary">
                        Lihat Detail
                    </a>
                </div>

            </div>

        @empty

            <div class="card empty-state">
                <h3>Belum Ada Laporan</h3>
                <p>Belum ada laporan yang tersedia dalam sistem.</p>
            </div>

        @endforelse

    </div>

    {{ $reports->links() }}

    {{-- PENCARIAN DAN FILTER --}}
    <div class="card fetch-section">

        <div class="section-header">
            <div>
                <h2>Pencarian dan Filter Laporan</h2>
                <p class="text-muted">
                    Cari laporan berdasarkan judul, status, atau wilayah.
                </p>
            </div>
        </div>

        <div class="filter-grid">

            <div class="form-group">
                <label for="search">Cari Judul Laporan</label>
                <input type="text" id="search"
                       placeholder="Masukkan judul laporan...">
            </div>

            <div class="form-group">
                <label for="status">Filter Status</label>
                <select id="status">
                    <option value="">Semua Status</option>
                    <option value="menunggu_peninjauan">Menunggu Peninjauan</option>
                    <option value="sedang_ditinjau">Sedang Ditinjau</option>
                    <option value="perlu_tindak_lanjut">Perlu Tindak Lanjut</option>
                    <option value="sedang_ditindaklanjuti">Sedang Ditindaklanjuti</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <div class="form-group">
                <label for="area_id">Filter Wilayah</label>
                <select id="area_id">
                    <option value="">Semua Wilayah</option>
                    @foreach (\App\Models\Area::all() as $area)
                        <option value="{{ $area->id }}">
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <button type="button" id="loadReports" class="btn btn-primary">
            Terapkan Filter
        </button>

        <p id="loadingMessage" class="loading-message"></p>

        <div id="reportList" class="fetch-report-list"></div>

        <div id="pagination"></div>

    </div>


    {{-- JAVASCRIPT FETCH --}}
    <script>
        const loadButton = document.getElementById('loadReports');
        const searchInput = document.getElementById('search');
        const statusFilter = document.getElementById('status');
        const areaFilter = document.getElementById('area_id');

        const statusLabels = {
            menunggu_peninjauan: 'Menunggu Peninjauan',
            sedang_ditinjau: 'Sedang Ditinjau',
            perlu_tindak_lanjut: 'Perlu Tindak Lanjut',
            sedang_ditindaklanjuti: 'Sedang Ditindaklanjuti',
            selesai: 'Selesai'
        };

        const statusClasses = {
            menunggu_peninjauan: 'status-waiting',
            sedang_ditinjau: 'status-review',
            perlu_tindak_lanjut: 'status-follow-up',
            sedang_ditindaklanjuti: 'status-progress',
            selesai: 'status-completed'
        };

        function displayPagination(result) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            if (result.last_page <= 1) {
                return;
            }

            const previousButton = document.createElement('button');
            previousButton.textContent = 'Sebelumnya';
            previousButton.disabled = result.current_page === 1;

            previousButton.addEventListener('click', function () {
                loadReports(result.current_page - 1);
            });

            const pageInfo = document.createElement('span');
            pageInfo.textContent =
                `Halaman ${result.current_page} dari ${result.last_page}`;

            const nextButton = document.createElement('button');
            nextButton.textContent = 'Selanjutnya';
            nextButton.disabled = result.current_page === result.last_page;

            nextButton.addEventListener('click', function () {
                loadReports(result.current_page + 1);
            });

            pagination.append(previousButton, pageInfo, nextButton);
        }

        function loadReports(page = 1) {
            const reportList = document.getElementById('reportList');
            const loadingMessage = document.getElementById('loadingMessage');

            const params = new URLSearchParams({
                search: searchInput.value,
                status: statusFilter.value,
                area_id: areaFilter.value,
                page: page
            });

            loadingMessage.textContent = 'Memuat data laporan...';
            reportList.innerHTML = '';

            fetch(`{{ route('reports.data') }}?${params}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal mengambil data laporan.');
                }

                return response.json();
            })
            .then(result => {
                loadingMessage.textContent = '';
                displayPagination(result);

                if (result.data.length === 0) {
                    reportList.textContent = 'Laporan tidak ditemukan.';
                    return;
                }

                result.data.forEach(report => {
                    const item = document.createElement('div');
                    item.className = 'report-card';

                    const header = document.createElement('div');
                    header.className = 'report-item-header';

                    const title = document.createElement('h3');
                    title.textContent = report.title;

                    const status = document.createElement('span');
                    status.className =
                        'status-badge ' +
                        (statusClasses[report.status] || '');

                    status.textContent =
                        statusLabels[report.status] || report.status;

                    header.append(title, status);

                    const meta = document.createElement('div');
                    meta.className = 'report-meta';

                    const reporter = document.createElement('p');
                    reporter.textContent = 'Pelapor: ' + report.user.name;

                    const area = document.createElement('p');
                    area.textContent = 'Wilayah: ' + report.area.name;

                    meta.append(reporter, area);

                    item.append(header, meta);
                    reportList.appendChild(item);
                });
            })
            .catch(error => {
                loadingMessage.textContent = error.message;
            });
        }

        loadButton.addEventListener('click', () => loadReports(1));
        searchInput.addEventListener('input', () => loadReports(1));
        statusFilter.addEventListener('change', () => loadReports(1));
        areaFilter.addEventListener('change', () => loadReports(1));

        loadReports();
    </script>

@endsection


@extends('layouts.app')

@section('title', 'Rekap Laporan')
@section('heading', 'Rekap Laporan')

@section('content')

<div class="page-header">
    <h1>Rekap Laporan Karhutla</h1>
    <p>Gambaran umum kondisi laporan berdasarkan wilayah.</p>
</div>

{{-- Filter wilayah --}}
<div class="card">
    <h3 class="section-title">Filter Rekap</h3>

    <form action="{{ route('recaps.index') }}" method="GET">
        <div class="form-group">
            <label for="area_id">Pilih Wilayah</label>

            <select name="area_id" id="area_id">
                <option value="">Semua Wilayah</option>

                @foreach ($areas as $area)
                    <option
                        value="{{ $area->id }}"
                        @selected($selectedArea == $area->id)
                    >
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Terapkan Filter
            </button>

            <a href="{{ route('recaps.index') }}" class="btn btn-secondary">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- Ringkasan laporan --}}
<div class="recap-summary">
    <div class="card recap-summary-card">
        <span>Total Laporan</span>
        <h2>{{ $totalReports }}</h2>
        <p>Seluruh laporan tercatat</p>
    </div>

    <div class="card recap-summary-card">
        <span>Laporan Aktif</span>
        <h2>{{ $activeReports }}</h2>
        <p>Laporan belum selesai</p>
    </div>

    <div class="card recap-summary-card">
        <span>Laporan Selesai</span>
        <h2>{{ $completedReports }}</h2>
        <p>Proses pemantauan selesai</p>
    </div>
</div>

{{-- Tabel rekap wilayah --}}
<div class="card">
    <div class="recap-table-header">
        <div>
            <h3 class="section-title">Rekap Berdasarkan Wilayah</h3>
            <p class="form-hint">
                Jumlah laporan berdasarkan wilayah kejadian.
            </p>
        </div>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Wilayah</th>
                    <th>Total Laporan</th>
                    <th>Laporan Aktif</th>
                    <th>Laporan Selesai</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($areaRecaps as $index => $area)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $area->name }}</td>
                        <td>{{ $area->total_reports }}</td>
                        <td>{{ $area->active_reports }}</td>
                        <td>{{ $area->completed_reports }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="table-empty">
                            Belum ada data rekap laporan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

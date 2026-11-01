@extends('layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')

    <div class="page-header">
        <h1>Selamat Datang, {{ auth()->user()->name }}!</h1>
        <p>
            Selamat datang di Sistem Pelaporan dan Pemantauan
            Kebakaran Hutan dan Lahan.
        </p>
    </div>

    <div class="card">
        <h2>Informasi Akun</h2>

        <p>
            <strong>Nama Pengguna:</strong>
            {{ auth()->user()->name }}
        </p>

        <p>
            <strong>Email:</strong>
            {{ auth()->user()->email }}
        </p>

        <p>
            <strong>Role:</strong>
            {{ ucfirst(auth()->user()->role) }}
        </p>
    </div>

    <div class="card">
        <h2>Akses Sistem</h2>

        @if (auth()->user()->role === 'pelapor')

            <p>
                Kamu dapat membuat laporan kejadian karhutla,
                melihat laporan, dan memantau status laporanmu.
            </p>

            <a href="{{ route('reports.index') }}" class="btn btn-primary">
                Lihat Laporan Saya
            </a>

            <a href="{{ route('reports.create') }}" class="btn btn-secondary">
                Buat Laporan
            </a>

        @elseif (auth()->user()->role === 'relawan')

            <p>
                Kamu dapat melakukan peninjauan terhadap laporan
                yang masuk dan memberikan catatan hasil peninjauan.
            </p>

            <a href="{{ route('reviews.index') }}" class="btn btn-primary">
                Mulai Peninjauan
            </a>

        @elseif (auth()->user()->role === 'koordinator')

            <p>
                Kamu dapat mengelola proses tindak lanjut,
                meneruskan laporan, dan menyelesaikan laporan.
            </p>

            <a href="{{ route('follow-ups.index') }}" class="btn btn-primary">
                Kelola Tindak Lanjut
            </a>

        @endif
    </div>

@endsection

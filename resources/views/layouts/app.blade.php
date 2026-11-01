<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') | Karhutla</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <div class="app-layout">

        {{-- SIDEBAR --}}
        <aside class="sidebar">

            <div class="sidebar-brand">
                <div class="brand-icon">K</div>
                <div>
                    <h2>KARHUTLA</h2>
                    <span>Sistem Pemantauan</span>
                </div>
            </div>


            <div class="sidebar-menu">
                <p class="menu-label">MENU UTAMA</p>

                <a href="{{ route('dashboard') }}"
                class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>

                @if (auth()->user()->role === 'pelapor')
                    <a href="{{ route('reports.index') }}"
                    class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        Laporan Saya
                    </a>

                @elseif (auth()->user()->role === 'relawan')
                    <a href="{{ route('reviews.index') }}"
                    class="menu-item {{ request()->routeIs('reviews.*') ? 'active' : '' }}">
                        Peninjauan Laporan
                    </a>

                    <a href="{{ route('reports.index') }}"
                    class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        Laporan
                    </a>

                    <a href="{{ route('recaps.index') }}"
                    class="menu-item {{ request()->routeIs('recaps.*') ? 'active' : '' }}">
                        Rekap Laporan
                    </a>

                @elseif (auth()->user()->role === 'koordinator')
                    <a href="{{ route('follow-ups.index') }}"
                    class="menu-item {{ request()->routeIs('follow-ups.*') ? 'active' : '' }}">
                        Tindak Lanjut
                    </a>

                    <a href="{{ route('reports.index') }}"
                    class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        Laporan
                    </a>

                    <a href="{{ route('recaps.index') }}"
                    class="menu-item {{ request()->routeIs('recaps.*') ? 'active' : '' }}">
                        Rekap Laporan
                    </a>
                @endif
            </div>


            <div class="sidebar-footer">
                <div class="user-info">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ ucfirst(auth()->user()->role) }}</span>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-button">
                        Keluar
                    </button>
                </form>
            </div>

        </aside>

        {{-- MAIN CONTENT --}}
        <main class="main-content">

            <header class="topbar">
                <div>
                    <h3>@yield('heading', 'Dashboard')</h3>
                    <p>Sistem Pelaporan dan Pemantauan Karhutla</p>
                </div>
            </header>

            <section class="page-content">
                @yield('content')
            </section>

        </main>

    </div>
@stack('scripts')
</body>
</html>

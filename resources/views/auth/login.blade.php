
@extends('layouts.guest')

@section('title', 'Login')

@section('content')

    <div class="login-container">

        <div class="brand-icon" style="margin: 0 auto 15px;">
            K
        </div>

        <h2>KARHUTLA</h2>

        <p class="subtitle">
            Sistem Pelaporan dan Pemantauan Karhutla
        </p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.authenticate') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary login-submit">
                Login
            </button>

        </form>

    </div>

@endsection

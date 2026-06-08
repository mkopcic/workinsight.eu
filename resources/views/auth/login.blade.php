@extends('layouts.guest')

@section('title', 'Prijava — WorkInsight')

@section('content')
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Prijava u račun</h2>

            @if (session('status'))
                <div class="alert alert-success" role="alert">{{ session('status') }}</div>
            @endif

            @error('email')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
            @enderror

            <form action="{{ route('login.store') }}" method="POST" autocomplete="off" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email adresa</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="vas@email.com" required autofocus>
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Lozinka
                        <span class="form-label-description">
                            <a href="{{ route('password.request') }}">Zaboravljena lozinka?</a>
                        </span>
                    </label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Vaša lozinka" required>
                </div>
                <div class="mb-2">
                    <label class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input">
                        <span class="form-check-label">Zapamti me na ovom uređaju</span>
                    </label>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">Prijava</button>
                </div>
            </form>
        </div>
    </div>
    <div class="text-center text-secondary mt-3">
        Nemate račun? Obratite se administratoru.
    </div>
@endsection

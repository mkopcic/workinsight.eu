@extends('layouts.guest')

@section('title', 'Zaboravljena lozinka — WorkInsight')

@section('content')
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-3">Zaboravljena lozinka</h2>
            <p class="text-secondary mb-4">Upišite email adresu i poslat ćemo vam link za postavljanje nove lozinke.</p>

            @if (session('status'))
                <div class="alert alert-success" role="alert">{{ session('status') }}</div>
            @endif

            @error('email')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
            @enderror

            <form action="{{ route('password.email') }}" method="POST" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email adresa</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="vas@email.com" required autofocus>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">Pošalji link za reset</button>
                </div>
            </form>
        </div>
    </div>
    <div class="text-center text-secondary mt-3">
        <a href="{{ route('login') }}">Natrag na prijavu</a>
    </div>
@endsection

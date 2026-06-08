@extends('layouts.guest')

@section('title', 'Nova lozinka — WorkInsight')

@section('content')
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Postavi novu lozinku</h2>

            @error('email')
                <div class="alert alert-danger" role="alert">{{ $message }}</div>
            @enderror

            <form action="{{ route('password.update') }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div class="mb-3">
                    <label class="form-label">Email adresa</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="vas@email.com" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nova lozinka</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Nova lozinka" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Ponovi lozinku</label>
                    <input type="password" name="password_confirmation"
                           class="form-control" placeholder="Ponovi novu lozinku" required>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">Spremi lozinku</button>
                </div>
            </form>
        </div>
    </div>
    <div class="text-center text-secondary mt-3">
        <a href="{{ route('login') }}">Natrag na prijavu</a>
    </div>
@endsection

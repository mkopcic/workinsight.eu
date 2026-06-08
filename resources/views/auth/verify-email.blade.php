@extends('layouts.guest')

@section('title', 'Potvrda emaila — WorkInsight')

@section('content')
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-3">Potvrdite email adresu</h2>
            <p class="text-secondary mb-4">
                Poslali smo vam link za potvrdu na email. Kliknite ga da nastavite.
                Ako ga niste primili, pošaljite novi.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success" role="alert">Novi link za potvrdu je poslan.</div>
            @endif

            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('verification.send') }}" class="flex-fill m-0">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">Pošalji link ponovno</button>
                </form>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-link">Odjava</button>
                </form>
            </div>
        </div>
    </div>
@endsection

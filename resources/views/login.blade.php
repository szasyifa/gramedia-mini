@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('login.auth') }}" class="form-fieldset w-50 mx-auto mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        {{-- token csrf: kunci agar data dari form bisa diambil controller --}}
        @csrf
        <h3 class="form-label text-center">LOGIN</h3>
        <div class="mb-3">
            <label class="form-label required" for="fieldset-email">Email</label>
            <input type="email" id="fieldset-email" name='email'
                class="form-control @error('email') is-invalid @enderror" autocomplete="email" required
                value="{{ old('email') }}" />
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="fieldset-phone">Password</label>
            <input type="password" id="fieldset-phone" name='password'
                class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required
                value="{{ old('password') }}" />
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type='submit' class="btn btn-primary">Kirim</button>
    </form>
@endsection

@extends('layouts.app')

@push('styles')
    <style>
        .book-cover-wrapper {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #f8f9fa;
        }

        .book-cover-wrapper img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-5">
        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>

        {{-- Detail paket --}}
        <div class="card border-0 shadow-sm mb-4"
            style="background: linear-gradient(135deg, #ffffff 0%, {{ $subscriptionPackage->color }} 100%);">
            <div class="card-body p-4">
                <h2 class="fw-bold text-dark mb-1">{{ $subscriptionPackage->name_package }}</h2>
                <p class="text-secondary">{{ $subscriptionPackage->description }}</p>
                <h4 class="text-dark mb-0">
                    Rp {{ number_format($subscriptionPackage->price, 0, ',', '.') }}
                </h4>
                <div class="mt-3">
                    <a href="{{ route('subscription-packages.payment', $subscriptionPackage->id) }}"
                        class="btn btn-primary">
                        <i class="me-2"></i>Langganan Sekarang
                    </a>
                </div>
            </div>
        </div>

        {{-- Search --}}
        <form action="{{ route('subscription_package.show', $subscriptionPackage->id) }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                    placeholder="Cari judul buku dalam paket ini...">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>

        {{-- Daftar buku --}}
        <div class="row g-4">
            @forelse ($packageBooks as $item)
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="book-cover-wrapper p-3">
                            <img src="{{ asset($item->book->cover) }}" alt="{{ $item->book->title }}">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <span style="font-size: 0.8rem" class="text-secondary text-truncate">
                                {{ $item->book->writer }}
                            </span>
                            <h5 style="font-size: 1rem; font-weight: bold;" class="text-dark text-truncate mb-0">
                                <a href="{{ route('books.show', $item->book->id) }}" class="text-dark"
                                    style="text-decoration: none;">{{ $item->book->title }}</a>
                            </h5>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Tidak ada buku ditemukan dalam paket ini.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

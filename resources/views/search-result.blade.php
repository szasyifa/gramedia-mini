@extends('layouts.app')

@push('styles')
    <style>
        .book-cover-wrapper {
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #f8f9fa;
            border-radius: 4px;
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
        <h2>Hasil Pencarian "{{ $query }}"</h2>

        @if ($books->isEmpty())
            <div class="alert alert-info mt-3">
                Tidak Ada Buku yang ditemukan untuk kata kunci <b>{{ $query }}</b>
            </div>
        @else
            <div class="row mt-3 g-3">
                @foreach ($books as $book)
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="book-cover-wrapper">
                                    <a href="{{ route('books.show', $book->id) }}">
                                        <img src='{{ asset($book->cover) }}' alt='{{ $book->title }}'>
                                    </a>
                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <p class="badge bg-secondary"><i class="fa-solid fa-mobile"></i></p>
                                    <p class="badge bg-secondary">3+ </p>
                                </div>
                                <div>
                                    <h5>
                                        <span style="font-size: 0.8rem"
                                            class="text-secondary">{{ $book->author ?? $book->writer }}</span>
                                        <br><a href="{{ route('books.show', $book->id) }}" class="text-dark"
                                            style="font-size: 1rem; text-decoration: none;">{{ $book->title }}</a>
                                    </h5>
                                    <h4 style="font-size: 1.2rem; font-weight:bold;">Rp.
                                        {{ number_format($book->price, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Kategori: <span class="text-primary">{{ $category->name }}</span></h2>
            <form action="{{ route('category.books', $category->id) }}" method="GET" class="d-flex gap-2">
                @if (request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif

                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="">Urutkan Berdasarkan</option>
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Buku Terbaru</option>
                    <option value="cheapest" {{ request('sort') == 'cheapest' ? 'selected' : '' }}>Harga Termurah</option>
                    <option value="expensive" {{ request('sort') == 'expensive' ? 'selected' : '' }}>Harga Termahal</option>
                </select>
            </form>
        </div>

        <form action="{{ route('category.books', $category->id) }}" method="GET" class="mb-4">
            @if (request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            <div class="input-group">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                    placeholder="Cari judul buku atau penulis dalam kategori ini...">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>

        <div class="row g-4">
            @forelse ($books as $book)
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="bg-light d-flex align-items-center justify-content-center p-3"
                            style="height: 220px; overflow: hidden;">
                            <img src="{{ asset($book->cover) }}" class="w-100 h-100" alt="Cover Buku"
                                style="object-fit: contain; max-height: 100%;">
                        </div>

                        <div class="card-body d-flex flex-column">
                            <span style="font-size: 0.8rem"
                                class="text-secondary text-truncate">{{ $book->author ?? $book->writer }}</span>
                            <h5 style="font-size: 1rem; font-weight: bold;" class="text-truncate mb-2">
                                <a href="{{ route('books.show', $book->id) }}" class="text-dark"
                                    style="text-decoration: none;">{{ $book->title }}</a>
                            </h5>
                            <h4 class="mt-auto text-primary" style="font-size: 1.1rem; font-weight:bold;">
                                Rp. {{ number_format($book->price, 0, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Tidak ada buku ditemukan dalam kategori ini.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

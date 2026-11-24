@extends('layouts.app')

@section('content')
<div class="card w-75 d-block mx-auto mt-3 shadow-sm">
    <div class="card-header bg-white py-3">
        <h1 class="h3 mb-0">Edit Buku</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger m-3 mb-0">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.books.update', $book->id) }}" enctype="multipart/form-data" class="card-body">
        @csrf
        @method('PUT')

        {{-- Kategori & Judul --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="book_category_id" class="form-label">Kategori Buku</label>
                <select name="book_category_id" id="book_category_id" class="form-select" required>
                    <option value="" disabled>Pilih Kategori Buku</option>
                    @foreach ($bookCategories as $bookCategory)
                        <option value="{{ $bookCategory->id }}" {{ old('book_category_id', $book->book_category_id) == $bookCategory->id ? 'selected' : '' }}>
                            {{ $bookCategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="title" class="form-label">Judul Buku</label>
                <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $book->title) }}" required>
            </div>
        </div>

        {{-- Penulis & Penerbit --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="writer" class="form-label">Penulis</label>
                <input type="text" class="form-control" name="writer" id="writer" value="{{ old('writer', $book->writer) }}" required>
            </div>

            <div class="col-md-6">
                <label for="publisher" class="form-label">Penerbit</label>
                <input type="text" class="form-control" name="publisher" id="publisher" value="{{ old('publisher', $book->publisher) }}" required>
            </div>
        </div>

        {{-- Harga & Bahasa --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="price" class="form-label">Harga</label>
                <input type="number" class="form-control" name="price" id="price" value="{{ old('price', $book->price) }}" min="0" required>
            </div>

            <div class="col-md-6">
                <label for="language" class="form-label">Bahasa</label>
                @php
                    $language = ['Indonesia', 'English', 'Japan', 'Arabic']
                @endphp
                <select class="form-select" name="language" id="language" required>
                    <option selected hidden disabled>Pilih Bahasa</option>
                    @foreach ($language as $lang)
                        <option value="{{ $lang }}"
                        {{ old('language', $book->language) == $lang ? 'selected' : '' }}>{{ $lang }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Tanggal Rilis & Jumlahalaman --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="release_date" class="form-label">Tanggal Rilis</label>
                <input type="date" class="form-control" name="release_date" id="release_date" value="{{ old('release_date', $book->release_date) }}" required>
            </div>

            <div class="col-md-6">
                <label for="page_of_book" class="form-label">Jumlah Halaman</label>
                <input type="number" class="form-control" name="page_of_book" id="page_of_book" value="{{ old('page_of_book', $book->page_of_book) }}" min="1" required>
            </div>
        </div>

        {{-- Sampul Buku --}}
        <div class="row mb-3">
            <div class="col-12">
                <label for="cover" class="form-label">
                    Sampul Buku <small class="text-muted"></small>
                </label>
                @if ($book->cover)
                    <div class="mb-2">
                        <img src="{{ asset($book->cover) }}" alt="Cover {{ $book->title }}"
                        class="img-thumbnail" style="max-height: 120px;">
                    </div>
                    <small class="text-muted d-block mt-1">Biarkan kosong jika tidak ingin mengubah sampul </small>
                @endif
                <input type="file" class="form-control" name="cover" id="cover" accept=".jpg,.jpeg,.png,.svg,.webp">
            </div>
        </div>

        {{-- Deskripsi Buku --}}
        <div class="row mb-3">
            <div class="col-12">
                <label for="description" class="form-label">Deskripsi Buku</label>
                <div id="description" style="height: 150px;"></div>
                <input type="hidden" name="description" id="description-input"
                value="{{ old('description', $book->description) }}">
            </div>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary">Perbarui Buku</button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.0.8/purify.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const descriptionInput = document.getElementById('description-input');

        const quill = new Quill('#description', {
            theme: 'snow'
        });

        if (descriptionInput.value) {
            quill.clipboard.dangerouslyPasteHTML(descriptionInput.value);
        }

        quill.on('text-change', function () {
            descriptionInput.value = quill.root.innerHTML;
        });

        const form = descriptionInput.closest('form');
        form.addEventListener('submit', function () {
            descriptionInput.value = quill.root.innerHTML;
        });
    });
</script>
@endpush

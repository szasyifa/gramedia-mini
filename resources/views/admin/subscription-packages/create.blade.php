@extends('layouts.app')

@section('content')
    <div class="card mt-5 w-50 mx-auto">
        <div class="card-header">
            <h3>Tambah Paket Langganan</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.subscription-packages.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Paket</label>
                    <input type="text" class="form-control @error('name_package') is-invalid @enderror" name="name_package" value="{{ old('name_package') }}" required>
                    @error('name_package')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" min="0" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required>
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Warna</label>
                    <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" name="color" value="{{ old('color', '#066fd1') }}" required>
                    @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea rows="3" class="form-control @error('description') is-invalid @enderror" name="description" required>{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.subscription-packages.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection

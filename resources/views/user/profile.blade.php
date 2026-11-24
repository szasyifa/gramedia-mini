@extends('layouts.app')

@section('content')
    <div class="container-xl mt-4">
        <div class="row g-4">
            {{-- KIRI : profil & ringkasan --}}
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 pb-3 border-bottom">
                            <span class="avatar avatar-md rounded-circle">
                                {{ Str::upper(Str::substr($user->name, 0, 2)) }}
                            </span>
                            <div class="fw-bold text-truncate">{{ $user->name }}</div>
                        </div>
                        <div class="mt-3">
                            <div class="text-primary fw-bold small mb-2">Profil Summary</div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-secondary">Koleksi Buku</span>
                                <span class="fw-bold">{{ $totalBooks }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-secondary">Langganan</span>
                                <span class="fw-bold">{{ $totalSubscriptions }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN : riwayat transaksi --}}
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h3 class="mb-0">Transaksi</h3>
                    <form method="GET" action="{{ route('profile') }}" style="min-width: 200px">
                        <label for="type" class="form-label small mb-1">Jenis Transaksi</label>
                        <select name="type" id="type" class="form-select" onchange="this.form.submit()">
                            <option value="subscription" {{ $type == 'subscription' ? 'selected' : '' }}>Paket Langganan
                            </option>
                            <option value="book" {{ $type == 'book' ? 'selected' : '' }}>Buku</option>
                        </select>
                    </form>
                </div>

                @if ($type == 'subscription')
                    @forelse ($subscriptionTransactions as $transaction)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-secondary">
                                        {{ $transaction->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                    </small>
                                    <span class="badge bg-green-lt">Transaksi Sukses</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-end mt-3">
                                    <div>
                                        <div class="fw-bold fs-3">{{ $transaction->subscriptionPackage->name_package }}
                                        </div>
                                        <span class="badge bg-red text-red-fg mt-1">
                                            Berlaku s/d :
                                            {{ \Carbon\Carbon::parse($transaction->expired_date)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-secondary d-block">Total Pembayaran</small>
                                        <span class="fw-bold fs-3">
                                            Rp {{ number_format($transaction->subscriptionPackage->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center">Belum ada riwayat pembelian paket langganan.</div>
                    @endforelse
                @else
                    <div class="alert alert-info text-center">Riwayat pembelian buku belum tersedia.</div>
                @endif
            </div>
        </div>
    </div>
@endsection

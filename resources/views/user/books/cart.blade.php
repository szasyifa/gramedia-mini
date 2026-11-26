@extends('layouts.app')

@section('content')
    <div class="container my-3 ">
        <div class="row">
            <h3>Keranjang belanja</h3>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- daftar buku -->
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <span class="cart-count" id="cart-count">0</span> Buku Terpilih</b>
                    </div>
                </div>

                @foreach ($carts as $cart)
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="row align-items-center g-3">
                                {{-- checkbox --}}
                                <div class="col-auto">
                                    <input type="checkbox" name="selected_carts[]" class="form-checkbox-input item-checkbox"
                                        value="{{ $cart->id }}" data-price="{{ $cart->display_price }}">
                                </div>
                                {{-- detail buku --}}
                                <div class="col">
                                    <div class="row row-0 flex-fill">
                                        {{-- gambar --}}
                                        <div class="col-md-3">
                                            <a href="">
                                                <img src="{{ asset($cart->book->cover ?? '') }}" class="img-fluid rounded"
                                                    alt="Card side image"
                                                    style="width: 170px; height: 250px; object-fit: cover;" />
                                            </a>
                                        </div>
                                        <div class="col">
                                            <div class="card-body h-full d-flex flex-column">
                                                <h3 class="card-title">
                                                    <div class="badge"><i class="fa-solid fa-mobile"></i>PDF</div>
                                                </h3>
                                                <div class="text-secondary">{{ $cart->book->writer ?? '-' }}
                                                    <br><span class="text-dark">{{ $cart->book->title ?? '-' }}</span>
                                                </div>
                                                <div class="mt-auto pt-3">
                                                    <h4 class="fa-bold mb-0">
                                                        @php
                                                            $price = 0;
                                                            if ($cart->subscription_package_id == null) {
                                                                $price = $cart->book->price ?? 0;
                                                            } else {
                                                                $price = $cart->subscriptionPackage->price ?? 0;
                                                            }
                                                        @endphp
                                                        <span class="text-dark">Rp
                                                            {{ number_format($cart->display_price, 0, ',', '.') }}</span>
                                                    </h4>
                                                </div>
                                                <form action="{{ route('cart.destroy', $cart->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm mt-2"
                                                        title="Hapus">
                                                        Hapus
                                                    </button>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <!-- Ringkasan harga -->
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Ringkasan keranjang</h5>
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Total Belanja(<span id="total-selected-count">{{ $carts->count() }}</span> Buku)</span>
                        </div>

                        <div class="mb-2">
                            @foreach ($carts as $cart)
                                <div class="summary-row d-flex justify-content-between small mb-1 {{ $cart->needs_package ? 'text-danger' : 'text-muted' }}"
                                    data-cart-id="{{ $cart->id }}">
                                    <span class="text-truncate pe-3">{{ $cart->book->title }}</span>
                                    <span class="fw-medium" style="text-align: right;">
                                        Rp{{ number_format($cart->display_price, 0, ',', '.') }}
                                        @if ($cart->needs_package)
                                            <br><small class="text-danger">Perlu membeli paket
                                                {{ $cart->subscriptionPackage->name_package }}
                                            </small>
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-3">

                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Total Belanja</span>
                            <h3 class="text-dark" id="total-price-text">Rp{{ number_format($totalPrice, 0, ',', '.') }}
                            </h3>
                        </div>

                        <form method="POST" action="{{ route('checkout.store') }}" id="form-checkout">
                            @csrf
                            <div id="checkout-inputs"></div>
                            <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-pill fw-semibold"
                                id="btn-checkout">
                                Checkout
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const totalPriceText = document.getElementById('total-price-text');
            const totalCountText = document.getElementById('total-selected-count');
            const cartCount = document.getElementById('cart-count');
            const btnCheckout = document.getElementById('btn-checkout');
            const checkoutInputs = document.getElementById('checkout-inputs');
            // FUNGSI FORMAT RUPIAH
            const rupiah = n => 'Rp ' + n.toLocaleString('id-ID');

            function updateSummary() {
                const checked = [...checkboxes].filter(cb => cb.checked);
                const total = checked.reduce(
                    (sum, cb) => sum + Number(cb.dataset.price), 0
                );

                totalPriceText.textContent = rupiah(total);
                totalCountText.textContent = checked.length;
                cartCount.textContent - checked.length;

                checkboxes.forEach(cb => {
                    const row = document.querySelector(
                        `.summary-row[data-cart-id="${cb.value}"]`
                    );
                    if (row) {
                        if (cb.checked) {
                            row.classList.remove('d-none');
                        } else {
                            row.classList.add('d-none');
                        }
                    }
                });
                btnCheckout.disabled = checked.length === 0;
                checkoutInputs.innerHTML = '';
                checked.forEach(cb => {
                    checkoutInputs.innerHTML +=
                        `<input type="hidden" name="selected_carts[]" value="${cb.value}">`;
                })
            }
            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateSummary);
            });
            updateSummary()
        });
    </script>
@endpush

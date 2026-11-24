@extends('layouts.app')
@push('styles')
    <style>
        .slick-prev::before,
        .slick-next::before {
            color: #333;
        }

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

        .book-cover-side {
            width: 100%;
            height: 120px;
            object-fit: contain;
        }
    </style>
@endpush
@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    <div id="carousel-sample" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="0" aria-label="Slide 1"
                aria-current="true" class="active"></button>
            <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carousel-sample" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" alt
                    src="https://i.pinimg.com/1200x/58/52/30/58523074e9d6b922d83e5a76de912392.jpg" />
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" alt
                    src="https://i.pinimg.com/1200x/58/52/30/58523074e9d6b922d83e5a76de912392.jpg" />
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" alt
                    src="https://i.pinimg.com/1200x/58/52/30/58523074e9d6b922d83e5a76de912392.jpg" />
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-sample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carousel-sample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    {{-- PAKET LANGGANAN --}}
    <div class="mt-4 p-5">
        <div class="d-flex align-items-center gap-2 mb-4 ">
            <span class="badge bg-yellow text-yellow-fg p-2">
                <i class="fa-solid fa-crown fs-3"></i>
            </span>
            <h2 class="mt-3 text-dark">Paket Langganan</h2>
        </div>
        <div class="row g-4">
            @forelse ($subscriptionPackages as $package)
                <div class="col-md-4">
                    <a href="{{ route('subscription_package.show', $package->id) }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0"
                            style="background: linear-gradient(135deg, #ffffff 0%, {{ $package->color }} 100%);">
                            <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                                <h3 class="fw-bold text-dark mb-1">{{ $package->name_package }}</h3>
                                <p class="text-secondary fw-bold mb-3" style="font-size: 0.9rem;">Package</p>
                                <div>
                                    <span class="text-secondary fs-6">Rp</span>
                                    <span style="font-size: 2rem; font-weight: bold;" class="text-dark">
                                        {{ number_format($package->price, 0, ',', '.') }}
                                    </span>
                                    <br>
                                    <span class="text-secondary" style="font-weight: bold; font-size: 0.85rem;">
                                        /{{ $package->duration ?? 30 }} days
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted">Belum ada paket langganan tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Buku Terbaru --}}
    <div class="mt-4">
        <div class="d-flex align-items-center gap-2 mb-4">
            <h2 class="mt-3 text-dark" style="font-weight: bold">Buku Baru Dirilis</h2>
        </div>
        <div id="wrapper-slider">
            {{-- Sesuaikan $latestBooks dengan variabel dari controller-mu --}}
            @forelse ($latesBooks as $book)
                <div class="px-2">
                    <div class="card h-100">
                        <div class="card-body">
                            <a href="{{ route('books.show', $book->id) }}">
                                <img src="{{ asset($book->cover) }}" alt="{{ $book->title }}" class="img-fluid rounded-3"
                                    style="height: 220px; width: 100%; object-fit: cover;">
                            </a>
                            <div class="d-flex gap-2 mt-2">
                                <p class="badge bg-secondary text-white"><i class="fa-solid fa-mobile"></i> PDF</p>
                                <p class="badge bg-secondary text-white">3+</p>
                            </div>
                            <div>
                                <h5>
                                    <span style="font-size: 0.8rem" class="text-secondary">{{ $book->writer }}</span> <br>
                                    <a href="{{ route('books.show', $book->id) }}"
                                        style="font-size: 1rem; text-decoration: none;"
                                        class="text-dark">{{ $book->title }}</a>
                                </h5>
                                <h4 style="font-size: 1.2rem; font-weight: bold;">Rp.
                                    {{ number_format($book->price, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">Tidak Ada Buku Terbaru</p>
            @endforelse
        </div>
    </div>
    {{-- Buku Gratis --}}
    <div class="mt-4 p-5">
        <div class="d-flex align-items-center gap-2 mb-4 mx-5">
            <h2 class="mt-3 text-dark" style="font-weight: bold">Buku Gratis</h2>
        </div>

        <div class="row">
            @forelse ($freeBooks as $book)
                <div class="col-4">
                    <div class="card d-flex flex-column">
                        <div class="row row-0 flex-fill">
                            <div class="col-md-3">
                                <a href="{{ route('books.show', $book->id) }}">
                                    <img src='{{ asset($book->cover) }}' alt='{{ $book->title }}'
                                        class="book-cover-side">
                                </a>
                            </div>
                            <div class="col">
                                <div class="card-body h-full d-flex flex-column">
                                    <h3 class="card-title">
                                        <div class="badge"><i class="fa-solid fa-mobile"></i>PDF</div>
                                    </h3>
                                    <div class="text-secondary">{{ $book->writer }}
                                        <br><a href="{{ route('books.show', $book->id) }}" class="text-dark"
                                            style="text-decoration: none;">{{ $book->title }}</a>
                                    </div>
                                    <div class="d-flex align-items-center pt-4 mt-auto">
                                        <h3>
                                            <span class="text-decoration-line-through text-secondary">Rp
                                                {{ number_format($book->price, 0, ',', ',') }}</span>
                                            <span class="text-dark">Rp.0</span>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#wrapper-slider').slick({
                dots: true,
                infinite: false,
                speed: 300,
                slidesToShow: 5,
                slidesToScroll: 5,
                responsive: [{
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        });
    </script>
@endpush

<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a href="{{ Auth::check() && Auth::user()->role == 'admin' ? route('admin.dashboard') : route('home') }}"
            class="navbar-brand navbar-brand-autodark me-3 text-decoration-none d-flex align-items-center gap-2">
            <span class="text-primary fs-2">
                <i class="fa-solid fa-book-open"></i>
            </span>
            <span class="fw-bold fs-2 text-dark">E-Books</span>
        </a>
        <!-- END NAVBAR LOGO -->
        <ul class="navbar-nav mx-auto w-50 py-2">
            {{-- DROPDOWN --}}
            @if (Auth::check() && Auth::user()->role == 'admin')
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.book-categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.book-categories.index') }}" class="nav-link">Kategori Buku</a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.subscription-packages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.subscription-packages.index') }}" class="nav-link">Paket Langganan</a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.books.index') }}" class="nav-link">Buku</a>
                </li>
            @else
                <div class="dropdown me-2">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">Kategori</button>
                    <div class="dropdown-menu dropdown-menu-card" style='min-width: 600px'>
                        <div class='p-3'>
                            <div class='row g-2'>
                                @foreach ($navbarCategories as $category)
                                    <div class="col-3">
                                        <a href="{{ route('category.books', $category->id) }}"
                                            class="text-decoration-none">
                                            <div class="card py-2 shadow-sm">
                                                <div class="card-body p-2 text-center text-dark fw-bold">
                                                    {{ $category->name }}</div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                {{-- SEARCH --}}
                <form method="GET" action="{{ route('search') }}" class="input-icon w-100">
                    <input type="text" name="q" value="{{ request('q') }}"
                        class="form-control form-control-rounded" placeholder="Cari judul, produk, buku, penulis" />
                    <button type="submit" class="input-icon-addon border-0 bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true" focusable="false" class="icon">
                            <path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                            <path d="M21 21l-6 -6" />
                        </svg>
                    </button>
                </form>

                {{-- KERANJANG --}}
                <div class="mt-2 ms-2">
                    <a href="{{ route('cart') }}"><i class="fa-solid fa-cart-shopping fs-3 text-dark"></i></a>
                </div>
            @endif
        </ul>
        <div class='d-flex align-items-center gap-2 order-md-last ms-auto'>
            @if (Auth::check())
                @if (Auth::user()->role != 'admin')
                    <a href="{{ route('profile') }}" title="Profil Saya"
                        class="avatar avatar-sm rounded-circle text-decoration-none {{ request()->routeIs('profile') ? 'bg-primary text-white' : '' }}">
                        {{ Str::upper(Str::substr(Auth::user()->name, 0, 2)) }}
                    </a>
                @endif
                <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
            @else
                <a href='{{ route('login') }}' class='btn btn-primary'>Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-light">Daftar</a>
            @endif
        </div>
    </div>
</header>

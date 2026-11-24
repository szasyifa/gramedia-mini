@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.subscription-package-books.store', $subscriptionPackage->id) }}"
        method = "POST">
        @csrf
        <div class="d-flex justify-content-between">
            <h1>Data Buku yang akan ditambahkan pada paket {{ $subscriptionPackage->name_package }}</h1>
            {{-- btn akan di sembunyikan jika belum memilih buku --}}
            {{-- 0 akan diganti sesuai jumlah yang diceklis  --}}
            <button type="submit" class="d-none btn btn-primary" id="btnBuku" ><span id="jmlhBuku">(0)</span>Tambah Buku</button>
        </div>

        <table class="table table-bordered" id="books-table">
            <thead>
                <tr>
                    <th>Pilih Buku</th>
                    <th></th>
                    <th>Sampul Buku</th>
                    <th>Judul</th>
                    <th>Kategori Buku</th>
                    <th>Penulis</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            const table = $('#books-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.subscription-package-books', ['packageId' => $subscriptionPackage->id]) }}",
                columns: [
                    {
                        // yang dikirim ke db ketika ceklis buku adalah id-nya
                        data: null, //mengirim semua attribute
                        orderable: false,
                        searchable: false,
                        // mengganti tampilan angka id menjadi checkbox
                        render: function(data) {
                            // name menggunakan array agar bisa memilih lebih dari 1
                            if (data.checkBook == true) {
                                return '';
                            } else {
                                return `<input type="checkbox" value="${data.id}" class="inputBookId" name="bookIds[]"> `;
                            }
                        }
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'cover',
                        name: 'cover',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'writer',
                        name: 'writer'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },

                ]
            });
        });
        // fungsi yang akan dipanggil tiap ada buku yang dipilih
        function updateSelectedBook() {
            // ambil jumlah class="inputBookId" yang di ceklis
            let selectedCount = $(".inputBookId:checked").length;
            // jumlahnya munculin ke HTML
            $("#jmlhBuku").text(selectedCount);

            // Memunculkan tombol dengan mengilangkan d-none
            if(selectedCount > 0) {
                $("#btnBuku").removeClass('d-none');
            } else {
                // kalau yang dipilih 0, d-none di tambahkan biar button hilang
                $("#btnBuku").addClass('d-none');
            }
        }
        // panggil fungsi tiap kali checkbox di ceklis atau sebaliknya(un-ceklis)
        $("#books-table tbody").on("change", ".inputBookId", function() {
            updateSelectedBook();
        });
    </script>
@endpush

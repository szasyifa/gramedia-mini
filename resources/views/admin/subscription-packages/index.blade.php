@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Manajemen Paket Langganan</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.subscription-packages.create') }}" class="btn btn-primary">Tambah Paket</a>
                <a href="{{ route('admin.subscription-packages.export') }}" class="btn btn-primary">Export Excel</a>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped w-100" id="packages-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Paketer</th>
                            <th>Harga</th>
                            <th>Warna</th>
                            <th>Deskripsi</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody> </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#packages-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.subscription-packages.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name_package',
                        name: 'name_package'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'color',
                        name: 'color'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush


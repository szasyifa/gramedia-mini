<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Books App</title>
    {{-- CDN TABLER --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.5.1/dist/css/tabler.min.css" />

    {{-- CDN FONTAWESOME --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css"
        integrity="sha512-QeR2VH+lsBE5LSAe1Q5EnTBbe7XTBubt8dG93Y7gidSgdMCr8nVqKcfKAMyN96SV8KDbZVTDXChatu5G2KQGzg=="
        crossorigin="anonymous" referrerpolicy="no-referrer">

    {{-- CDN JQUERY --}}
    <script src="https://code.jquery.com/jquery-3.7.1.slim.js"
        integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>

    {{-- CDN SCLICKS --}}
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

    {{-- CDN DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/3.0.4/css/dataTables.dataTables.min.css" />

    {{-- CDN QuillJS --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />


    <style>
        html,
        body {
            overflow-x: hidden;
        }
    </style>
    @stack('styles')
</head>

<body>
    <x-navbar />
    @yield('content')
    {{-- @yield sebagai placeholder untuk menampung konten dinamis, yang nilainya akan diisi ketika file layout tersebut digunakan --}}

    {{-- CDN TABLER --}}
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.5.1/dist/js/tabler.min.js"></script>

    {{-- CDN SLICKS --}}
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    {{-- CDN DataTables --}}
    <script src="https://cdn.datatables.net/3.0.4/js/dataTables.min.js"></script>

    {{-- CDN QuillJS --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>


    @stack('scripts')
    <x-footer />
</body>

</html>

<!-- resources/views/motif/show.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Motif</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="{{ asset('pengunjung/css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('pengunjung/css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('pengunjung/css/main.css') }}">

    <!-- script
    ================================================== -->
    <script src="{{ asset('pengunjung/js/modernizr.js') }}"></script>
    <script src="{{ asset('pengunjung/js/pace.min.js') }}"></script>

    <!-- favicons
    ================================================== -->
    <link rel="icon" href="{{ asset('pengunjung/images/sascode-logo.jpg') }}" type="image/x-icon">

    <!-- font-awesome
    ================================================== -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
</head>
<body>

    <style>
        p {
            margin: 25px;
            color: white;
        }

        .s-header {
            z-index: 600;
            width: 100%;
            height: 78px;
            background-color: #0e1113;
            position: fixed; /* Mengubah dari absolute ke fixed */
            top: 0; /* Menempatkan header di bagian atas */
            left: 0; /* Menempatkan header di sisi kiri */
            transition: all 0.3s ease-in-out;
        }

        .s-header > .row {
            max-width: 1300px;
            height: 78px;
            position: relative;
            }

        .s-header.sticky {
            background-color: #0e1113;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
            position: fixed;
            top: 0;
            } 
            
        .gambar-motif {
            display: block;
            max-width: 100%;
            height: auto;
            margin-top: 78px;
        }

    </style>

    <div class="_navbar">
        @include('pengunjung.partials._navbar-motif')
    </div>

    @if(isset($motifData['gambarUrl']))
        <img class="gambar-motif" src="{{ $motifData['gambarUrl'] }}" alt="Gambar Motif">
    @endif
    <h1 style="color: white; margin:6px; margin-top: 35px; text-align: center;"> <mark style="background-color: #8C3061; color:white; padding:7px; border-radius:8px;">{{ $motifData['motif'] }}<mark> </h1>
    <p style="text-align: center">{{ $motifData['filosofi'] }}</p>
    <p><strong>Sumber Arti: <br></strong> {{ $motifData['sumberFilosofi'] }}</p>
    <p><strong>Sumber Gambar: <br></strong> {{ $motifData['sumberGambar'] }}</p>

    <div class="_footer">
        @include('pengunjung.partials._footer-motif')
    </div>

    <!-- Java Script
    ================================================== -->
    <script src="{{ asset('pengunjung/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('pengunjung/js/plugins.js') }}"></script>
    <script src="{{ asset('pengunjung/js/main.js') }}"></script>
</body>
</html>

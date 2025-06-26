<!DOCTYPE html>
<html class="no-js" lang="en">
<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>Sasindai by Thiesa</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">


</head>

<body id="top">

    <!-- preloader
    ================================================== -->
    <div id="preloader">
        <div id="loader" class="dots-jump">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div class="_navbar">
        @include('pengunjung.partials._navbar')
    </div>

    <!-- home
    ================================================== -->
    <section id="home" class="s-home target-section" data-parallax="scroll" data-image-src="" data-natural-width=3000 data-natural-height=2000 data-position-y=center>

        <div class="shadow-overlay"></div>

        <div class="home-content">

            <div class="row home-content__main">

                <div class="home-content__left">
                    <h1>
                        Belajar motif Sasirangan dengan cara seru dan interaktif.
                    </h1>
    
                    <h3>
                        Sasindai hadir untuk kamu yang ingin mengenal motif Sasirangan dengan cara asik dan interaktif. Lewat aplikasi ini, kamu bisa belajar sejarah dan makna tiap motif tanpa bosan, langsung dari ponselmu. Yuk, lestarikan budaya Banua bareng Sasindai!                   
                    </h3>
                    <div class="home-content__btn-wrap">
                        <a href="https://mega.nz/file/ahxnlKQa#n1Gkw4_PpblPWzyB3gobvZvgtZnnt_rabsFgdtyYDa8" target="_blank" class="btn btn--primary home-content__btn">
                            Dapatkan Aplikasi
                        </a>
                    </div>
                </div> <!-- end home-content__left-->

                <div class="home-content__right">
                    <img src="{{ asset('pengunjung/images/mockup.png') }}" style="width: 600px; height: auto; object-fit: cover;" srcset="{{ asset('pengunjung/images/mockup.png 1x, pengunjung/images/mockup.png 2x') }}">
                </div> <!-- end home-content__right -->
            </div> <!-- end home-content__main -->

            <ul class="home-content__social">
                <li><a href="https://wa.me/6289696210706/"  target="_blank">WhatsApp</a></li>
                <li><a href="https://www.instagram.com/thiesasories/" target="_blank">Instagram</a></li>
            </ul>

        </div> <!-- end home-content -->

        <a href="#tentang" class="home-scroll smoothscroll">
            <span class="home-scroll__text">Scroll</span>
            <span class="home-scroll__icon"></span>
        </a> 

    </section> <!-- end s-home -->


    <!-- about
    ================================================== -->
    <section id="tentang" class="s-about target-section" style="background: #8D0B41">

        <div class="row section-header has-bottom-sep" data-aos="fade-up">
            <div class="col-full">
                <h1 class="display-1">
                    Apa itu SASINDAI ?
                </h1>
                <p class="lead">
                    Sasindai adalah aplikasi lapak online edukatif berbasis Android yang dikembangkan oleh Toko Thiesa (By Thiesa) untuk memperkenalkan arti di balik motif kain Sasirangan sebagai warisan budaya khas Kalimantan Selatan. Aplikasi ini memadukan teknologi modern seperti Augmented Reality (AR) untuk menampilkan produk kain Sasirangan secara nyata, serta menyediakan fitur katalog tentang setiap motif kain. Dengan Sasindai, pengguna dapat belajar tentang sejarah, makna, dan keindahan kain Sasirangan dengan cara yang interaktif dan menyenangkan. Aplikasi ini bertujuan untuk melestarikan dan mempromosikan budaya lokal melalui teknologi digital, sehingga generasi muda dapat lebih mengenal dan mencintai warisan budaya mereka.
                </p>
            </div>
        </div> <!-- end section-header -->

    </section> <!-- end s-about -->


    <!-- about-how
    ================================================== -->
    <section id="fitur" class="s-about-how target-section">

        <div class="row">
           <div class="col-full video-bg" data-aos="fade-up">

                <div class="shadow-overlay"></div>

                <a class="btn-video" href="https://youtu.be/KF9lPq3zOpU?feature=shared" data-lity>
                    <span class="video-icon"></span>
                </a>
           </div> <!-- end video-bg -->
        </div>

        <div class="row process-wrap">

            <h2 class="display-2 text-center" data-aos="fade-up">Temukan Fitur</h2>

            <div class="process" data-aos="fade-up">
                <div class="process__steps block-1-2 block-tab-full group">

                    <div class="col-block step">
                        <h3 class="item-title">S-Katalog</h3>
                        <p>
                            S-Katalog adalah platform yang mengungkap arti di balik setiap motif kain sasirangan. 
                            Melalui katalog ini, Anda dapat memahami arti dari setiap desain 
                            yang mencerminkan nilai budaya dan tradisi Kalimantan.
                        </p> 
                    </div>

                    <div class="col-block step">
                        <h3 class="item-title">Ka Pasaran</h3>
                        <p>
                            Ka Pasaran adalah fitur yang menghubungkan pengguna dengan produk kain sasirangan by Thiesa. 
                            Dengan fitur ini, pengguna dapat menemukan dan membeli produk kain sasirangan khas Kalimantan Selatan.
                        </p> 
                    </div>
               
                    <div class="col-block step">
                        <h3 class="item-title">AR Produk</h3>
                        <p>
                            Fitur AR Produk memungkinkan pengguna untuk melihat produk kain sasirangan dalam bentuk Augmented Reality. 
                            Dengan fitur ini, pengguna dapat merasakan pengalaman interaktif yang membawa mereka lebih dekat dengan produk yang ditawarkan.
                        </p> 
                    </div>

                    <div class="col-block step">
                        <h3 class="item-title">Rilis Media</h3>
                        <p>
                            Dapatkan informasi terbaru dan terpercaya seputar aplikasi Sasindai, inovasi, serta kolaborasi kami. Ikuti rilis
                             media untuk menjelajahi cerita di balik pengembangan teknologi yang memadukan seni dan budaya tradisional.
                        </p> 
                    </div>  

                </div> <!-- end process__steps -->
           </div> <!-- end process -->
        </div> <!-- end about-how -->

    </section> <!-- end s-about-how -->


    <!-- features
    ================================================== -->
    <section id="minimum-perangkat" class="s-features target-section">

        <div class="row section-header has-bottom-sep" data-aos="fade-up">
            <div class="col-full">
                <h1 class="display-1">
                    Kebutuhan Minimum Perangkat
                </h1>
                <p class="lead">
                    Agar aplikasi dapat berfungsi dengan optimal, 
                    perangkat Anda harus memenuhi spesifikasi minimum yang tercantum di bawah ini.
                </p>
            </div>
        </div> <!-- end section-header -->

        <div class="row features block-1-3 block-m-1-2">

            <div class="col-block item-feature" data-aos="fade-up">
                <div class="item-feature__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="50" height="50"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#8D0B41" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                </div>
                <div class="item-feature__text">
                    <h3 class="item-title">Minimum</h3>
                    <p>
                        <strong>Sistem Operasi:</strong> Android 10 (API 29)<br>
                        <strong>RAM:</strong> 2 GB<br>
                        <strong>Penyimpanan:</strong> 150 MB kosong<br>
                        <strong>Fitur Wajib:</strong> Kamera<br>
                        <strong>Internet:</strong> Diperlukan<br>
                    </p>
                </div>
            </div>

            <div class="col-block item-feature" data-aos="fade-up">
                <div class="item-feature__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="50" height="50"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#8D0B41" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                </div>
                <div class="item-feature__text">
                    <h3 class="item-title">Disarankan</h3>
                    <p>
                        <strong>Sistem Operasi:</strong> Android 12 (API 31)<br>
                        <strong>RAM:</strong> 4 GB<br>
                        <strong>Penyimpanan:</strong> 300 MB kosong<br>
                        <strong>Fitur Wajib:</strong> Kamera<br>
                        <strong>Internet:</strong> Diperlukan<br>
                    </p>
                </div>
            </div>

            <div class="col-block item-feature" data-aos="fade-up">
                <div class="item-feature__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="50" height="50"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path fill="#8D0B41" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/></svg>
                </div>
                <div class="item-feature__text">
                    <h3 class="item-title">Ideal</h3>
                    <p>
                        <strong>Sistem Operasi:</strong> Android 13 / 14<br>
                        <strong>RAM:</strong> 6 GB atau lebih<br>
                        <strong>Penyimpanan:</strong> 500 MB kosong<br>
                        <strong>Fitur Wajib:</strong> Kamera<br>
                        <strong>Internet:</strong> Diperlukan<br>
                    </p>
                </div>
            </div>

        </div> <!-- end features -->

    </section> <!-- end s-features -->


    <!-- pricing
    ================================================== -->
    <!--<section id="mitraxsascode" class="s-pricing target-section">

        <div class="row section-header align-center" data-aos="fade-up">
            <div class="col-full">
                <h1 class="display-1">
                   Program Ekslusif <br> "Mitra x SASCODE"
                </h1>
                <p class="lead">
                    Program yang ditujukan untuk menciptakan sinergi antara 
                    SASCODE dan toko Sasirangan dalam mengembangkan potensi bisnis lokal.
                </p>
                <a href="{{ route('register-mitra') }}" class="btn btn--primary" style="background: #8D0B41; border:none; outline:none">Bergabung</a>
                <a href="" class="btn btn--primary" style="margin-left:10px; background: #8D0B41; border:none; outline:none">Konsultasikan</a>
            </div>
        </div> end section-header

    </section> end s-pricing -->


    <!-- footer
    ================================================== -->
    <div class="_footer">
        @include('pengunjung.partials._footer')
    </div> <!-- end s-footer -->


    <!-- Java Script
    ================================================== -->
    <script src="{{ asset('pengunjung/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('pengunjung/js/plugins.js') }}"></script>
    <script src="{{ asset('pengunjung/js/main.js') }}"></script>

</body>
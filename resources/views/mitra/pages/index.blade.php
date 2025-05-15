<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Beranda</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/base/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/card/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/alerts/style-floating.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('pengunjung/images/sascode-logo.jpg') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <div class="_navbar">
        @include('mitra.partials._navbar')
    </div>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <div class="_sidebar">
        @include('mitra.partials._sidebar')
      </div>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">

          <div id="statusAlert" class="floating-alert-error" style="display: none; font-size: 14px;">
            <div class="alert-content">
                Status toko Anda belum terverifikasi. Anda tidak dapat menambahkan produk.
            </div>
          </div>


          @if ($tokoBelumLengkap)
          <div style="width: 100%; background-color: #ffe5e5; border-left: 5px solid #f44336; padding: 16px; margin-bottom: 27px; font-size: 14px;">
              <div style="display: flex; align-items: center; justify-content: space-between;">
                  <div style="display: flex; align-items: center;">
                      <span style="font-weight: bold; color: #f44336; margin-right: 10px;">⚠️</span>
                      <span style="color: #333;">
                          @if (!$statusVerifikasi)
                              Anda belum melengkapi data toko. Silakan <a href="{{ url('/data-toko') }}" style="color: #d32f2f; font-weight: bold;">lengkapi sekarang</a>.
                          @elseif ($statusVerifikasi == 'pending')
                              Data toko Anda sedang dalam proses verifikasi.
                          @elseif ($statusVerifikasi == 'rejected')
                              Data toko Anda ditolak. Silakan <a href="" style="color: #d32f2f; font-weight: bold;">perbaiki data</a>.
                          @endif
                      </span>
                  </div>
              </div>
          </div>

          @endif        

          <div class="row"> 
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h4>Selamat datang, 
                      @if (session('session') && is_array(session('session')))
                          @php
                            $Data = session('session');    
                          @endphp
                          <span class="nav-profile-name">{{ $Data['namaLengkap'] ?? 'Email tidak ditemukan' }}</span>
                      @endif
                      ({{ $mitraRole ?? 'Role tidak ditemukan'}})
                    </h4>
                    <p class="mb-md-0">
                      Ciptakan dan kembangkan konten yang luar biasa!
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <a href="#" style="margin-left: 7px; font-size: 20px; text-decoration: none">
                      <i class="mdi mdi-plus-circle-outline" style="font-size: 24px; vertical-align: middle; color: #522258;"></i>
                      <span onclick="return checkTokoStatus('{{ $statusVerifikasi }}')" style="cursor: pointer; color: #522258; font-size: 18px;">
                        Tambah produk
                      </span>
                    </a>              
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body dashboard-tabs p-0">
                  <ul class="nav nav-tabs px-4" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">RINGKASAN</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="sales-tab" data-toggle="tab" href="#petunjuk" role="tab" aria-controls="sales" aria-selected="false">PETUNJUK</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="akun-tab" data-toggle="tab" href="#akun" role="tab" aria-controls="akun" aria-selected="false">TENTANG AKUN</a>
                    </li>
                  </ul>
                  <div class="tab-content py-0 px-0">
                    <!-- Bagian Overview -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-tag mr-3 icon-lg text-danger"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Total Produk Dijual</small>
                            <h5 class="mr-2 mb-0">0 buah</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-basket mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Total Pesanan Masuk</small>
                            <h5 class="mr-2 mb-0">0 pesanan</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-package mr-3 icon-lg text-warning"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Pengiriman Barang</small>
                            <h5 class="mr-2 mb-0">0 barang</h5>
                          </div>
                        </div>
                        <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-coin mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Penghasilan</small>
                            <h5 class="mr-2 mb-0">Rp ---.---</h5>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Bagian Pentunjuk -->
                    <div class="tab-pane fade" id="petunjuk" role="tabpanel" aria-labelledby="sales-tab">
                      <h4 style="margin-left: 20px; margin-top: 20px; color: #522258;">Publikasi</h4>
                      <div class="d-flex flex-wrap justify-content-xl-between">

                        <!-- Petunjuk s-katalog -->
                        <div class="card-card">
                          <div class="card-card-header">
                            <h4>S-Katalog</h4>
                          </div>
                          <div class="card-card-body">
                            <p><strong>Ringkasan:</strong> S-Katalog disusun untuk mempublikasikan berbagai motif kain sasirangan beserta filosofi motif, mulai dari motif dasar hingga yang terbaru. Informasi lebih lanjut lihat <a href="{{ url('/faq') }}">FAQ</a>.</p>
                          </div>
                          <div class="card-card-footer" onclick="window.location.href='{{ route('katalog') }}';" style="cursor: pointer;">
                            <i class="fas fa-arrow-right" style="margin-right: 7px"></i> Arahkan ke "S-Katalog"
                          </div>
                        </div>

                        <!-- Petunjuk ka pasran -->
                        <div class="card-card">
                          <div class="card-card-header">
                            <h4>Ka Pasaran</h4>
                          </div>
                          <div class="card-card-body">
                            <p><strong>Ringkasan:</strong> Ka Pasar disusun untuk mempublikasikan pasar yang menjalin kerja sama melalui fitur <strong>Mitra x SASCODE</strong>. Informasi lebih lanjut dapat ditemukan pada bagian <a href="{{ url('/faq') }}">FAQ</a>.</p>
                          </div>
                          <div class="card-card-footer" onclick="window.location.href='{{ route('ka-pasar') }}';" style="cursor: pointer;">
                            <i class="fas fa-arrow-right" style="margin-right: 7px"></i> Arahkan ke "Ka Pasar"
                          </div>
                        </div>

                        <!-- Petunjuk artikel -->
                        <div class="card-card">
                          <div class="card-card-header">
                            <h4>Artikel</h4>
                          </div>
                          <div class="card-card-body">
                            <p><strong>Ringkasan:</strong> Artikel disusun untuk mempublikasikan informasi seputar kegiatan dan event terkait sasirangan melalui media. Informasi lebih lanjut lihat <a href="{{ url('/faq') }}">FAQ</a>.</p>
                          </div>
                          <div class="card-card-footer" onclick="window.location.href='{{ route('berita') }}';" style="cursor: pointer;">
                            <i class="fas fa-arrow-right" style="margin-right: 7px"></i> Arahkan ke "Artikel"
                          </div>
                        </div>
                        
                      </div>
                    </div>

                    <!-- Bagian Pengaturan Akun -->
                    <div class="tab-pane fade" id="akun" role="tabpanel" aria-labelledby="akun-tab">
                      <h4 style="margin-left: 20px; margin-top: 20px; color: #522258;">Tentang Akun</h4>
                      <div class="d-flex flex-wrap justify-content-xl-between" style="padding: 20px">
                        <form class="forms-sample w-100" method="GET" action="{{ route('dashboard', ['uid' => $uid] )}}">
                          <div class="form-group row">
                            <!-- Kolom E-Mail -->
                            <label for="email" class="col-sm-3 col-form-label">E-Mail</label>
                            <div class="col-sm-9">
                              <input type="email" class="form-control" id="email" name="email" value="{{ $mitraData['email'] ?? '' }}" placeholder="E-Mail" readonly>
                            </div>

                            <!-- Kolom Phone -->
                            <label for="phone" class="col-sm-3 col-form-label">Nomor Telepon</label>
                            <div class="col-sm-9">
                              <input type="text" class="form-control" id="phone" name="phone" value="{{ $mitraData['noTelp'] ?? '' }}" placeholder="Nomor telepon tidak ditemukan" readonly>
                            </div>

                            <!-- Kolom Role -->
                            <label for="role" class="col-sm-3 col-form-label">Role</label>
                            <div class="col-sm-9">
                              <input type="text" class="form-control" id="role" name="role" value="{{ $mitraRole}}" placeholder="Role" readonly>
                            </div>

                            <!-- Kolom Auth Method -->
                            <label for="auth_method" class="col-sm-3 col-form-label">Metode Autentikasi</label>
                            <div class="col-sm-9">
                              <input type="text" class="form-control" id="auth_method" name="auth_method" value="{{ $mitraData['authMethod'] ?? 'kosong' }}" placeholder="Metode Autentikasi" readonly>
                            </div>

                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <div class="_footer">
            @include('mitra.partials._footer')
        </div>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script>
    function checkTokoStatus(status) {
        if (status !== 'accepted') {
            const alertBox = document.getElementById('statusAlert');
            alertBox.style.display = 'block';
    
            // Auto close after 4s
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 4000);

            return false;
        } else {
            // Redirect ke halaman tambah produk
            window.location.href = "{{ route('produk') }}";
            return true;
        }
    }
  </script>

  <script src="{{ asset('vendors/base/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('vendors/datatables.net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ asset('js/off-canvas.js') }}"></script>
  <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('js/template.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('js/dashboard.js') }}"></script>
  <script src="{{ asset('js/data-table.js') }}"></script>
  <script src="{{ asset('js/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script>
  <!-- End custom js for this page-->
</body>

</html>


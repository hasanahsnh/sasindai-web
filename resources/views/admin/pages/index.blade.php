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
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('pengunjung/images/sascode-logo.jpg') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">


</head>
<body style="font-family: Poppins">
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <div class="_navbar">
        @include('admin.partials._navbar')
    </div>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <div class="_sidebar">
        @include('admin.partials._sidebar')
      </div>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h4>Selamat datang kembali, 
                      @if (session('session') && is_array(session('session')))
                          @php
                            $Data = session('session');    
                          @endphp
                          <span class="nav-profile-name">{{ $Data['namaLengkap'] ?? 'Email tidak ditemukan' }}</span>
                      @endif
                      ({{ $adminRole ?? 'Role tidak ditemukan'}})
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
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body dashboard-tabs p-0">
                  <ul class="nav nav-tabs px-4" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">RINGKASAN</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="sales-tab" data-toggle="tab" href="#petunjuk" role="tab" aria-controls="sales" aria-selected="false">ARAHKAN</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="akun-tab" data-toggle="tab" href="#akun" role="tab" aria-controls="akun" aria-selected="false">TENTANG AKUN</a>
                    </li>
                    <!-- <li class="nav-item">
                      <a class="nav-link" id="riwayat-tab" data-toggle="tab" href="#riwayat" role="tab" aria-controls="akun" aria-selected="false">RIWAYAT LOGIN</a>
                    </li>-->
                  </ul>
                  <div class="tab-content py-0 px-0">
                    <!-- Bagian Overview -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-account-multiple mr-3 icon-lg text-danger"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Pengguna Sasindai</small>
                            <h5 class="mr-2 mb-0">{{ $totalActiveUsers}} pengguna</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-barley mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Motif Sasirangan</small>
                            <h5 class="mr-2 mb-0">{{ $totalKatalogs}} motif</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-barley mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Objek 3D Produk</small>
                            <h5 class="mr-2 mb-0">{{ $totalKatalogs}} motif</h5>
                          </div>
                        </div>
                        <div class="d-flex py-3 border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-newspaper mr-3 icon-lg text-danger"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Konten Artikel</small>
                            <h5 class="mr-2 mb-0">{{ $totalArtikels}} artikel</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-basket mr-3 icon-lg text-warning"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Mitra</small>
                            <h5 class="mr-2 mb-0">{{ $totalPasars}} toko</h5>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Bagian Pentunjuk -->
                    <div class="tab-pane fade" id="petunjuk" role="tabpanel" aria-labelledby="sales-tab">
                      <h4 style="margin-left: 20px; margin-top: 20px; color: #522258;">Arahkan cepat</h4>
                      <div class="d-flex flex-wrap justify-content-xl-between">

                        <!-- Petunjuk mitra -->
                        <div class="card-card">
                          <div class="card-card-header">
                            <h4>Data Mitra</h4>
                          </div>
                          <div class="card-card-body">
                            <p><strong>Ringkasan:</strong> S-Katalog disusun untuk mempublikasikan berbagai motif kain sasirangan beserta arti motif. Informasi lebih lanjut lihat <a href="{{ url('/faq') }}">FAQ</a>.</p>
                          </div>
                          <div class="card-card-footer" onclick="window.location.href='{{ route('ka-pasar') }}';" style="cursor: pointer;">
                            <i class="fas fa-arrow-right" style="margin-right: 7px"></i> Arahkan ke Data Mitra
                          </div>
                        </div>

                        <!-- Petunjuk pengambilan barang -->
                        <div class="card-card">
                          <div class="card-card-body">
                            <iframe width="100%" height="200" src="https://www.youtube.com/embed/usUbg4Zs-LI?si=G5fZX-YHHJj3Q_uI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                          </div>
                        </div>

                        <!-- Petunjuk ka pasran -->
                        <div class="card-card">
                          <div class="card-card-header">
                            <h4>Objek 3D Produk (AR)</h4>
                          </div>
                          <div class="card-card-body">
                            <p><strong>Ringkasan:</strong> Ka Pasaran disusun untuk mempublikasikan pasar yang menjalin kerja sama melalui fitur <strong>Mitra x SASCODE</strong>. Informasi lebih lanjut dapat ditemukan pada bagian <a href="{{ url('/faq') }}">FAQ</a>.</p>
                          </div>
                          <div class="card-card-footer" onclick="window.location.href='{{ route('objek.3d') }}';" style="cursor: pointer;">
                            <i class="fas fa-arrow-right" style="margin-right: 7px"></i> Arahkan ke Objek 3D Produk (AR)
                          </div>
                        </div>

                        <!-- Petunjuk artikel -->
                        <div class="card-card">
                          <div class="card-card-header">
                            <h4>Rilis Media</h4>
                          </div>
                          <div class="card-card-body">
                            <p><strong>Ringkasan:</strong> Artikel disusun untuk mempublikasikan informasi seputar kegiatan dan event terkait sasirangan melalui media. Informasi lebih lanjut lihat <a href="{{ url('/faq') }}">FAQ</a>.</p>
                          </div>
                          <div class="card-card-footer" onclick="window.location.href='{{ route('berita') }}';" style="cursor: pointer;">
                            <i class="fas fa-arrow-right" style="margin-right: 7px"></i> Arahkan ke Rilis Media
                          </div>
                        </div>

                        <!-- Petunjuk s-katalog -->
                        <div class="card-card">
                          <div class="card-card-header">
                            <h4>S-Katalog</h4>
                          </div>
                          <div class="card-card-body">
                            <p><strong>Ringkasan:</strong> S-Katalog disusun untuk mempublikasikan berbagai motif kain sasirangan beserta arti motif. Informasi lebih lanjut lihat <a href="{{ url('/faq') }}">FAQ</a>.</p>
                          </div>
                          <div class="card-card-footer" onclick="window.location.href='{{ route('katalog') }}';" style="cursor: pointer;">
                            <i class="fas fa-arrow-right" style="margin-right: 7px"></i> Arahkan ke S-Katalog
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
                              <input type="email" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="email" name="email" value="{{ $adminData['email'] ?? '' }}" placeholder="E-Mail" readonly>
                            </div>

                            <!-- Kolom Phone -->
                            <label for="phone" class="col-sm-3 col-form-label">Nomor Telepon</label>
                            <div class="col-sm-9">
                              <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="phone" name="phone" value="{{ $adminData['noTelp'] ?? '' }}" placeholder="Nomor telepon tidak ditemukan" readonly>
                            </div>

                            <!-- Kolom Role -->
                            <label for="role" class="col-sm-3 col-form-label">Role</label>
                            <div class="col-sm-9">
                              <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="role" name="role" value="{{ $adminRole}}" placeholder="Role" readonly>
                            </div>

                            <!-- Kolom Auth Method -->
                            <label for="auth_method" class="col-sm-3 col-form-label">Metode Autentikasi</label>
                            <div class="col-sm-9">
                              <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="auth_method" name="auth_method" value="{{ $adminData['authMethod'] ?? '' }}" placeholder="Metode Autentikasi" readonly>
                            </div>

                          </div>
                        </form>
                      </div>
                    </div>

                    <!-- Bagian aktivitas login 
                    <div class="tab-pane fade" id="riwayat" role="tabpanel" aria-labelledby="riwayat-tab">
                      <h4 style="margin-left: 20px; margin-top: 20px; color: #522258;">Riwayat Login</h4>
                      <div class="d-flex flex-wrap justify-content-xl-between" style="padding: 20px">
                        <div class="table-responsive">
                          <table class="table table-hover w-100">
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Perangkat</th>
                                <th>IP Address</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>-->

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <div class="_footer">
            @include('admin.partials._footer')
        </div>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
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


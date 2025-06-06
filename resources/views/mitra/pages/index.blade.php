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
                  </ul>
                  <div class="tab-content py-0 px-0">
                    <!-- Bagian Overview -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-tag mr-3 icon-lg text-danger"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Produk dijual</small>
                            <h5 class="mr-2 mb-0">0 buah</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-basket mr-3 icon-lg text-information"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Total pesanan</small>
                            <h5 class="mr-2 mb-0">0 pesanan</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-package mr-3 icon-lg text-warning"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Pesanan perlu dikirim</small>
                            <h5 class="mr-2 mb-0">0 pesanan</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <i class="mdi mdi-truck mr-3 icon-lg text-success"></i>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted">Pesanan dikirim</small>
                            <h5 class="mr-2 mb-0">0 barang</h5>
                          </div>
                        </div>
                      </div>

                      <!-- div akun terhubung -->
                      <div class="card-card">
                        <div class="card-card-header">
                          <h4>Akun Terhubung</h4>
                        </div>
                        <div class="row">
                          <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                              <div class="card-body dashboard-tabs p-0">
                                <div class="table-responsive">
                                  <table class="table table-hover w-100">
                                    <thead>
                                      <tr>
                                        <th>Account</th>
                                        <th>Action</th> 
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>
                                          Midtrans Payment
                                        </td>
                                        <td>
                                          <a href="https://dashboard.midtrans.com/login" target="_blank" title="Login midtrans"
                                            style="background-color: green; padding: 10px; border-radius: 5px; display: inline-block; vertical-align: middle;">
                                            <i class="fas fa-external-link-alt" style="color: white;"></i>
                                          </a>
                                          <a href="#" title="Informasi"
                                            style="margin-left: 8px; background-color: blue; padding: 10px; border-radius: 5px; display: inline-block; vertical-align: middle;">
                                            <i class="fas fa-info-circle" style="color: white;"></i>
                                          </a>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- div orderan masuk -->
                      <div class="card-card">
                        <div class="card-card-header"  style="display: flex; justify-content: space-between; align-items: center;">
                          <h4>Pesanan yang harus segera dikirim</h4>
                          <a href="" target="blank" title="Lihat lebih banyak">
                            <i class="fas fa-external-link-alt" style="color: blue;"></i>
                          </a> 
                        </div>
                        <div class="row">
                          <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                              <div class="card-body dashboard-tabs p-0">
                                <div class="table-responsive">
                                  <table class="table table-hover w-100">
                                    <thead>
                                      <tr>
                                        <th>ID Pesanan</th>
                                        <th>Status Pesanan</th>
                                        <th>Status Pembayaran</th>
                                        <th>Telah dibayar pada</th>
                                        <th>Action</th> 
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @if ($filteredPesanans && count($filteredPesanans) > 0)
                                        @foreach ($filteredPesanans as $key => $item)
                                          @if (!empty($item['order_id']) ||
                                          !empty($item['statusPesanan']) ||
                                          !empty($item['status']))
                                          <tr>
                                            <td>
                                              {{ $item['order_id'] ?? 'ID Pesanan tidak ditemukan'}}
                                            </td>
                                            <td>
                                              <p style="display: inline-block; font-size: 14px; background-color: orange; color: black; padding: 5px 10px; border-radius: 5px;">
                                                {{ $item['statusPesanan'] ?? 'Status Pesanan tidak ditemukan' }}
                                              </p>
                                              <p style="display: inline-block; font-size: 14px; background-color: red; color: white; padding: 5px 10px; border-radius: 5px;">
                                                Segera lakukan pengiriman
                                              </p>
                                            </td>
                                            <td>
                                              <p style="display: inline-block; font-size: 14px; background-color: green; color: white; padding: 5px 10px; border-radius: 5px;">
                                                {{ $item['status'] ?? 'Status Pembayaran tidak ditemukan'}}
                                              </p>
                                            </td>
                                            <td>
                                              <p style="display: inline-block; font-size: 14px;">
                                                {{ $item['updated_at'] ?? 'Waktu pembayaran tidak ditemukan'}}
                                              </p>
                                            </td>
                                            <td>
                                              <a href="" data-toggle="modal" data-target="#modalInputResi{{ $key }}" title="Input resi" style="background-color: yellow; padding: 10px; border-radius: 5px">
                                                <i class="fas fa-truck" style="color: black;"></i>
                                              </a> 
                                            </td>
                                          </tr>

                                          <!-- Modal Edit Produk -->
                                          <div class="modal fade" id="modalInputResi{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 700px; width: 100%;">
                                              <div class="modal-content rounded-15" >
                                                <div class="modal-body p-4 px-5">
                                                  <div class="main-content text-center">
                                                    <a href="#" class="close-btn" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true"><span class="icon-close2"></span></span>
                                                    </a>
                                                    <div class="card-body">
                                                      <h4 class="card-title" style="color: black">Input No Resi Pengiriman</h4>
                                                      <form action="" method="POST" enctype="multipart/form-data" id="formEdit{{ $key }}" class="forms-sample">
                                                        <div class="form-group row">
                                                          <label for="id_produk" class="col-sm-3 col-form-label" style="color: black; font-weight: bold; ;">ID Pesanan</label>
                                                          <div class="col-sm-9">
                                                            <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" name="key" value="{{ $key }}" readonly>
                                                          </div>

                                                          <label for="ekspedisi" class="col-sm-3 col-form-label" style="color: black; font-weight: bold;">Layanan Pengiriman</label>
                                                          <div class="col-sm-9">
                                                            <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" name="key" placeholder="Masukkan layanan pengiriman" required>
                                                          </div>

                                                          <label for="input_resi" class="col-sm-3 col-form-label" style="color: black; font-weight: bold;">No Resi Pengiriman</label>
                                                          <div class="col-sm-9">
                                                            <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" name="key" placeholder="Masukkan No. Resi pengiriman" required>
                                                          </div>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary mr-2">Simpan Perubahan</button>
                                                      </form>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>

                                          @endif
                                        @endforeach
                                      @else
                                        <tr>
                                          <td colspan="5" style="text-align: center">Data Pesanan yang perlu dikirim tidak ditemukan</td>
                                        </tr>
                                      @endif
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>

                    <!-- Bagian Pentunjuk -->
                    <div class="tab-pane fade" id="petunjuk" role="tabpanel" aria-labelledby="sales-tab">
                      <h4 style="margin-left: 20px; margin-top: 20px; color: #522258;">Arahkan cepat</h4>
                      <div class="d-flex flex-wrap justify-content-xl-between">

                        <!-- Petunjuk s-katalog -->
                        <div class="card-card">
                          <div class="card-card-header">
                            <h4>Informasi Toko</h4>
                          </div>
                          <div class="card-card-body">
                            <p><strong>Ringkasan:</strong> S-Katalog disusun untuk mempublikasikan berbagai motif kain sasirangan beserta filosofi motif, mulai dari motif dasar hingga yang terbaru. Informasi lebih lanjut lihat <a href="{{ url('/faq') }}">FAQ</a>.</p>
                          </div>
                          <div class="card-card-footer" onclick="window.location.href='';" style="cursor: pointer;">
                            <i class="fas fa-arrow-right" style="margin-right: 7px"></i> Arahkan ke Informasi Toko/Usaha
                          </div>
                        </div>

                        <!-- Petunjuk pengambilan barang -->
                        <div class="card-card">
                          <div class="card-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                            <h4>Tutorial scan model 3D menggunakan aplikasi Kiri Engine</h4>
                            <div style="display: flex; gap: 19px;">
                              <a href="https://play.google.com/store/apps/details?id=com.kiriengine.app" target="_blank" title="Dapatkan aplikasi Kiri Engine versi Android">
                                <i class="fab fa-android" style="color: rgb(91, 255, 88);"></i>
                              </a>
                              <a href="https://apps.apple.com/us/app/kiri-engine-3d-scanner-lidar/id1577127142" target="_blank" title="Dapatkan aplikasi Kiri Engine versi iOS">
                                <i class="fab fa-apple" style="color: black;"></i>
                              </a>
                            </div>
                          </div>
                          <div class="card-card-body">
                            <iframe width="100%" height="315" src="https://www.youtube.com/embed/Ku7kvhRWXjs?si=hrb_lmyfr8r3HMKp" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                          </div>
                        </div>

                        <!-- Petunjuk s-katalog -->
                        <div class="card-card">
                          <div class="card-card-header">
                            <h4>Data Produk</h4>
                          </div>
                          <div class="card-card-body">
                            <p><strong>Ringkasan:</strong> S-Katalog disusun untuk mempublikasikan berbagai motif kain sasirangan beserta filosofi motif, mulai dari motif dasar hingga yang terbaru. Informasi lebih lanjut lihat <a href="{{ url('/faq') }}">FAQ</a>.</p>
                          </div>
                          <div class="card-card-footer" onclick="window.location.href='';" style="cursor: pointer;">
                            <i class="fas fa-arrow-right" style="margin-right: 7px"></i> Arahkan ke Data Produk
                          </div>
                        </div>

                        <!-- Petunjuk ka pasran -->
                        <div class="card-card">
                          <div class="card-card-header">
                            <h4>Pesanan</h4>
                          </div>
                          <div class="card-card-body">
                            <p><strong>Ringkasan:</strong> Ka Pasar disusun untuk mempublikasikan pasar yang menjalin kerja sama melalui fitur <strong>Mitra x SASCODE</strong>. Informasi lebih lanjut dapat ditemukan pada bagian <a href="{{ url('/faq') }}">FAQ</a>.</p>
                          </div>
                          <div class="card-card-footer" onclick="window.location.href='';" style="cursor: pointer;">
                            <i class="fas fa-arrow-right" style="margin-right: 7px"></i> Arahkan ke Pesanan
                          </div>
                        </div>

                        <!-- Petunjuk artikel -->
                        <div class="card-card">
                          <div class="card-card-header">
                            <h4>Pengiriman</h4>
                          </div>
                          <div class="card-card-body">
                            <p><strong>Ringkasan:</strong> Artikel disusun untuk mempublikasikan informasi seputar kegiatan dan event terkait sasirangan melalui media. Informasi lebih lanjut lihat <a href="{{ url('/faq') }}">FAQ</a>.</p>
                          </div>
                          <div class="card-card-footer" onclick="window.location.href='';" style="cursor: pointer;">
                            <i class="fas fa-arrow-right" style="margin-right: 7px"></i> Arahkan ke Pengiriman
                          </div>
                        </div>
                        
                      </div>
                    </div>

                    <!-- Bagian Pengaturan Akun -->
                    <div class="tab-pane fade" id="akun" role="tabpanel" aria-labelledby="akun-tab">
                      <h4 style="margin-left: 20px; margin-top: 20px; color: #522258;">Tentang Akun</h4>
                      <div class="d-flex flex-wrap justify-content-xl-between" style="padding: 20px">
                        <form class="forms-sample w-100" method="GET" action="{{ route('dashboard-mitra', ['uid' => $uid] )}}">
                          <div class="form-group row">
                            <!-- Kolom E-Mail -->
                            <label for="email" class="col-sm-3 col-form-label">E-Mail</label>
                            <div class="col-sm-9">
                              <input type="email" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="email" name="email" value="{{ $mitraData['email'] ?? '' }}" placeholder="E-Mail" readonly>
                            </div>

                            <!-- Kolom Phone -->
                            <label for="phone" class="col-sm-3 col-form-label">Nomor Telepon</label>
                            <div class="col-sm-9">
                              <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="phone" name="phone" value="{{ $mitraData['noTelp'] ?? '' }}" placeholder="Nomor telepon tidak ditemukan" readonly>
                            </div>

                            <!-- Kolom Role -->
                            <label for="role" class="col-sm-3 col-form-label">Role</label>
                            <div class="col-sm-9">
                              <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="role" name="role" value="{{ $mitraRole}}" placeholder="Role" readonly>
                            </div>

                            <!-- Kolom Auth Method -->
                            <label for="auth_method" class="col-sm-3 col-form-label">Metode Autentikasi</label>
                            <div class="col-sm-9">
                              <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="auth_method" name="auth_method" value="{{ $mitraData['authMethod'] ?? 'kosong' }}" placeholder="Metode Autentikasi" readonly>
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


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ka Pasaran</title>
  
  <!-- Plugins & Styles -->
  <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/base/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/alerts/style-success.css') }}">
  <link rel="stylesheet" href="{{ asset('css/alerts/style-error.css') }}">
  <link rel="shortcut icon" href="{{ asset('pengunjung/images/sascode-logo.jpg') }}">

  <!-- Font & Icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
 <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <div class="_navbar">
      @include('mitra.partials._navbar')
    </div>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_sidebar.html -->
      <div class="_sidebar">
        @include('mitra.partials._sidebar')
      </div>
      <!-- partial -->
      <div class="main-panel">

        <div class="content-wrapper">

          @if(session('success'))
          <div class="custom-alert-success">
              <span class="alert-icon-success">!</span>
              <span class="alert-message-success">{{ session('success') }}</span>
              <button class="alert-close-success" onclick="this.parentElement.style.display='none';">&times;</button>
          </div>
          @elseif(session('error'))
          <div class="custom-alert-error">
              <span class="alert-icon-error">!</span>
              <span class="alert-message-error">{{ session('error') }}</span>
              <button class="alert-close-error" onclick="this.parentElement.style.display='none';">&times;</button>
          </div>
          @endif

          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">

                  <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-3">
                      <h1 class="card-title" style="font-size:16px; color:black; border-bottom: 2px solid #8D0B41; padding-bottom: 10px;">
                          DATA PENGIRIMAN
                      </h1>
                  </div>

                  <div class="table-responsive">
                        <table class="table table-hover w-100">
                          <thead>
                            <tr>
                              <th>ID Pesanan</th>
                              <th>Status Pengiriman</th>
                              <th>Kurir</th>
                              <th>Resi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (isset($dataPengiriman) && count($dataPengiriman) > 0)
                              @foreach ($dataPengiriman as $pengiriman)
                                <tr>
                                  <td>{{ $pengiriman['idPesanan'] }}</td>
                                  <td>
                                    @php
                                        $status = strtolower($pengiriman['statusPengiriman'] ?? 'Status tidak diketahui');
                                        switch ($status) {
                                          case 'aktif':
                                            $class = 'status-dikirim';
                                            $text = 'Pengiriman aktif';
                                            break;
                                          case 'pengiriman selesai':
                                            $class = 'status-diterima';
                                            $text = 'Pesanan diterima';
                                            break;
                                          default:
                                            $class = 'status-default';
                                            $text = 'Status pengiriman tidak diketahui';
                                        }
                                    @endphp

                                    <p class="status-badge {{ $class }}">{{ $text }}</p>
                                  </td>
                                  <td>{{ $pengiriman['kurirPesanan'] ?? 'Tidak ada' }}</td>
                                  <td>{{ $pengiriman['resiPesanan'] ?? 'Tidak ada' }}</td>
                                </tr>
                              @endforeach
                            @else
                              <tr>
                                <td colspan="4" class="text-center">Tidak ada data pengiriman</td>
                              </tr>
                            @endif
                          </tbody>
                        </table>
                  </div>


              </div>
            </div>
          </div>

        </div>

        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        <div class="_footer">
          @include('mitra.partials._footer')
        </div>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>

  <!-- Scripts -->
  <script src="{{ asset('vendors/base/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('js/off-canvas.js') }}"></script>
  <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('js/template.js') }}"></script>
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
            window.location.href = "{{ route('pesanan') }}";
            return true;
        }
    }
  </script>

</body>
</html>

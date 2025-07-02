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
                          DATA PESANAN
                      </h1>

                      <form method="GET" action="{{ url('/pesanan') }}" class="d-flex flex-wrap align-items-center gap-2 mt-3 mt-md-0">

                          {{-- Label --}}
                          <label for="status_pesanan" class="col-form-label mb-0" style="font-size: 14px; color: black; margin-right: 20px;">
                              Status Pesanan:
                          </label>

                          {{-- Dropdown --}}
                          <select name="status_pesanan" id="status_pesanan" class="form-select"
                                  style="min-width: 200px; padding: 8px; font-size: 14px; border-radius: 5px; margin-right: 20px;">
                              <option value="">Semua</option>
                              <option value="menunggu pembayaran" {{ request('status_pesanan') == 'menunggu pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                              <option value="dikemas" {{ request('status_pesanan') == 'dikemas' ? 'selected' : '' }}>Dikemas</option>
                              <option value="cancel" {{ request('status_pesanan') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                              <option value="dikirim" {{ request('status_pesanan') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                              <option value="pesanan diterima" {{ request('status_pesanan') == 'pesanan diterima' ? 'selected' : '' }}>Pesanan Diterima</option>
                          </select>

                          {{-- Tombol Filter --}}
                          <button type="submit"
                                  title="Terapkan Filter"
                                  style="background-color: #8D0B41; padding: 10px; border-radius: 5px; border: none; margin-right: 15px;">
                              <i class="fas fa-filter" style="color: white;"></i>
                          </button>

                      </form>
                  </div>

                  <div class="table-responsive">
                        <table class="table table-hover w-100">
                          <thead>
                            <tr>
                              <th>Cetak Rincian Pesanan</th>
                              <th>ID Pesanan</th>
                              <th>Status Pesanan</th>
                              <th>Status Pembayaran</th>
                              <th>Telah dibayar pada</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (!empty($filteredPesanan))
                            @foreach ($filteredPesanan as $key => $item)
                                          <tr>
                                            <td>
                                              <a href="{{ route('print.rincian.pesanan', ['order_id' => $key]) }}" 
                                                title="Cetak rincian pesanan"
                                                style="background-color: rgb(172, 33, 89); padding: 10px; border-radius: 5px"
                                                target="_blank">
                                                  <i class="fas fa-print" style="color: white;"></i>
                                              </a> 
                                            </td>
                                            <td>
                                              {{ $item['orderId'] ?? 'ID Pesanan tidak ditemukan'}}
                                            </td>
                                            <td>
                                             @php
                                                  $status = strtolower($item['statusPesanan'] ?? 'tidak diketahui');

                                                  switch ($status) {
                                                      case 'menunggu pembayaran':
                                                          $class = 'status-menunggu';
                                                          $text = 'Menunggu Pembayaran';
                                                          break;
                                                      case 'dikemas':
                                                          $class = 'status-dikemas';
                                                          $text = 'Segera lakukan pengiriman';
                                                          break;
                                                      case 'dikirim':
                                                          $class = 'status-dikirim';
                                                          $text = 'Dikirim';
                                                          break;
                                                      case 'cancel':
                                                      case 'dibatalkan':
                                                          $class = 'status-cancel';
                                                          $text = 'Dibatalkan';
                                                          break;
                                                      case 'pesanan diterima':
                                                          $class = 'status-diterima';
                                                          $text = 'Pesanan Diterima';
                                                          break;
                                                      default:
                                                          $class = 'status-default';
                                                          $text = 'Status tidak diketahui';
                                                  }
                                              @endphp

                                              <p class="status-badge {{ $class }}">
                                                  {{ $text }}
                                              </p>
                                            </td>
                                            <td>
                                              @php
                                                  $statusBayar = strtolower($item['status'] ?? 'tidak diketahui');

                                                  switch ($statusBayar) {
                                                      case 'pending':
                                                          $class = 'status-pending';
                                                          $text = 'Tertunda';
                                                          break;
                                                      case 'success':
                                                          $class = 'status-success';
                                                          $text = 'Berhasil';
                                                          break;
                                                      case 'cancel':
                                                          $class = 'status-cancel';
                                                          $text = 'Dibatalkan';
                                                          break;
                                                      default:
                                                          $class = 'status-default';
                                                          $text = 'Status Pembayaran tidak ditemukan';
                                                  }
                                              @endphp

                                              <p class="status-badge {{ $class }}">
                                                  {{ $text }}
                                              </p>
                                            </td>
                                            <td>
                                              <p style="display: inline-block; font-size: 14px;">
                                                {{ $item['updated_at'] ?? 'Waktu pembayaran tidak ditemukan'}}
                                              </p>
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
                                                <label for="id_produk" class="col-sm-3 col-form-label" style="color: black;">ID Pesanan</label>
                                                  <div class="col-sm-9">
                                                      <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" name="key" value="{{ $key }}" readonly>
                                                  </div>

                                                <label for="ekspedisi" class="col-sm-3 col-form-label" style="color: black;">Layanan Pengiriman</label>
                                                  <div class="col-sm-9">
                                                      <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" name="key" value="{{ $item['kurir'] }}" placeholder="Masukkan layanan pengiriman" required>
                                                  </div>

                                                <label for="input_resi" class="col-sm-3 col-form-label" style="color: black;">No Resi Pengiriman</label>
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

                              @endforeach
                              @else
                              <tr>
                                <td colspan="6" class="text-center">Tidak ada data pesanan.</td>
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

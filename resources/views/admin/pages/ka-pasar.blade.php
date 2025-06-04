<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ka Pasaran</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/base/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/alerts/style-success.css') }}">
  <link rel="stylesheet" href="{{ asset('css/alerts/style-error.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('pengunjung/images/sascode-logo.jpg') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Modal -->
</head>

<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <div class="_navbar">
      @include('admin.partials._navbar')
    </div>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_sidebar.html -->
      <div class="_sidebar">
        @include('admin.partials._sidebar')
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
                <div class="d-flex align-items-center mb-3">
                  <h1 class="card-title" style="font-size:16px; color:black; display: inline-block; border-bottom: 2px solid #522258; padding-bottom: 10px;">
                    DATA MITRA
                  </h1>

                  <!-- Dropdown Menu for smaller screens -->
                  <div class="ms-3 d-md-none">
                    <div class="dropdown">
                      <a class="mdi mdi-dots-vertical" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                      style="font-size: 20px; margin-left:20px">
                      </a>
                    </div>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-hover w-100">
                    <thead>
                      <tr>
                        <th>ID Toko</th>
                        <th>UID Pengguna</th>
                        <th>Nama Lengkap Owner</th>
                        <th>Nama Toko</th>
                        <th>Alamat Toko</th>
                        <th>No Telepon</th>
                        <th>Bank Tujuan</th>
                        <th>No Rekening Tujuan</th>
                        <th>Status Verifikasi Toko</th>
                        <th>Perbarui Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- tr fields -->
                      @if($pasars && count($pasars) > 0)
                        @foreach ($pasars as $key => $item)
                        @if(
                          !empty($item['idToko']) || 
                          !empty($item['uid']) || 
                          !empty($item['namaLengkap']) ||
                          !empty($item['namaToko']) || 
                          !empty($item['alamatToko']) ||
                          !empty($item['noTelp']) ||
                          !empty($item['bank']) ||
                          !empty($item['noRekning']) ||
                          !empty($item['statusVerifikasiToko']))
                          <tr>
                            <td>{{ $item['idToko'] ?? 'ID Toko tidak ditemukan'}}</td>
                            <td>{{ substr($item['uid'] ?? 'UID tidak ditemukan', 0, 4) . str_repeat('*', 4)}}</td>
                            <td>{{ $item['namaLengkap'] ?? 'Nama owner tidak ditemukan'}}</td>
                            <td>{{ $item['namaToko'] ?? 'Nama toko tidak ditemukan'}}</td>
                            <td>{{ $item['alamatToko'] ?? 'Alamat toko tidak ditemukan'}}</td>
                            <td>{{ $item['noTelp'] ?? 'No Telepon toko tidak ditemukan'}}</td>
                            <td>{{ $item['bank'] ?? 'Bank tujuan tidak ditemukan'}}</td>
                            <td>{{ $item['noRekening'] ?? 'No Rekening tujuan tidak ditemukan'}}</td>
                            <td>{{ $item['statusVerifikasiToko'] ?? 'Status verifikasi toko tidak ditemukan'}}</td>
                            <td>
                              <form action="{{ url('/perbarui-status-toko') }}" method="POST">
                                @csrf
                                <input type="hidden" name="key" value="{{ $key }}">
                                <select name="statusVerifikasi" onchange="this.form.submit()" class="form-select">
                                  <option value="">-- Pilih status --</option>
                                  <option value="pending" {{ ($item['statusVerifikasi'] ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                                  <option value="rejected" {{ ($item['statusVerifikasi'] ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                  <option value="accepted" {{ ($item['statusVerifikasi'] ?? '') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                </select>
                              </form>
                            </td>
                          </tr>

                        @endif
                        @endforeach
                      @else
                        <tr>
                          <td colspan="8" style="text-align: center">Data Toko tidak ditemukan</td>
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
          @include('admin.partials._footer')
        </div>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>

  

  <!-- container-scroller -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        fetch('/gomaps-script')
            .then(response => response.json())
            .then(data => {
                const script = document.createElement('script');
                script.src = data.script_url;
                script.async = true;
                script.defer = true;
                script.onload = () => {
                  document.querySelectorAll('.forms-sample').forEach((form, index) => {
                      const key = form.id.replace('formEdit', '');
                      initMap(key);
                  });
                };
                document.head.appendChild(script);
            })
            .catch(error => console.error('Error fetching gomaps script:', error));
    });
  </script>

  <script type="text/javascript" src="{{ asset('js/mapInput.js') }}"></script>
  
  <script>
    function resetForm(formId) {
      document.getElementById(formId).reset();
    }
  </script>
  <!-- plugins:js -->
  <script src="{{ asset('vendors/base/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ asset('js/off-canvas.js') }}"></script>
  <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('js/template.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <!-- End custom js for this page-->
</body>

</html>

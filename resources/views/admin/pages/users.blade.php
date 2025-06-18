<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Pengguna Aplikasi</title>
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
                    PENGGUNA APLIKASI
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
                        <th>UID</th>
                        <th>Email</th>
                        <th>Nama Lengkap</th>
                        <th>Nomor Telepon</th>
                        <th>User Level (Role)</th>
                        <th>Metode Autentikasi</th>
                        <th>Email Terverifikasi?</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- tr fields -->
                      @if($users && count($users) > 0)
                        @foreach ($users as $key => $item)
                        @if(!empty($item['uid']) || !empty($item['email']) || !empty($item['namaLengkap']) || !empty($item['noTelp']) || !empty($item['role']) || !empty($item['authMethod']) || !empty($item['emailIsVerified']))
                          <tr>
                            <td>{{ substr($item['uid'] ?? 'UID tidak ditemukan', 0, 4) . str_repeat('*', 4) }}</td>
                            <td>{{ $item['email'] ?? 'Email tidak ditemukan' }}</td>
                            <td>{{ $item['namaLengkap'] ?? 'Nama lengkap tidak ditemukan'}}</td>
                            <td>
                              @if (empty($item['noTelp']))
                                Nomor telepon tidak ditemukan
                              @else
                                {{ $item['noTelp'] }}                              
                              @endif
                            </td>
                            <td>{{ $item['role'] ?? 'Role tidak ditemukan' || '0'}}</td>
                            <td>{{ $item['authMethod'] ?? 'Metode Autentikasi tidak ditemukan' }}</td>
                            <td>
                              {{ isset($item['emailIsVerified']) 
                                  ? ($item['emailIsVerified'] ? 'Sudah diverifikasi' : 'Belum diverifikasi') 
                                  : 'Status verifikasi tidak ditemukan' 
                              }}
                            </td>
                          </tr>
                        @endif
                        @endforeach
                      @else
                        <tr>
                          <td colspan="5" style="text-align: center">Data berita tidak ditemukan</td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
                <!-- Modal Tambah -->
                <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 700px; width: 100%;">
                    <div class="modal-content rounded-0">
                      <div class="modal-body p-4 px-5">
                        <!-- Isi modal -->
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End Modal Tambah -->
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

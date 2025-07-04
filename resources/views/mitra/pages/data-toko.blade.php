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
  <link rel="stylesheet" href="{{ asset('css/alerts/style-floating.css') }}">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('pengunjung/images/sascode-logo.jpg') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Modal -->
  
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

          <div id="statusAlert" class="floating-alert-error-web" style="display: none; font-size: 14px;">
            <div class="alert-content">
                Status toko Anda belum terverifikasi.
            </div>
          </div>
  

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
                  <h1 class="card-title" style="font-size:16px; color:black; display: inline-block; border-bottom: 2px solid #8D0B41; padding-bottom: 10px;">
                    DATA MITRA
                  </h1>
                </div>
                <div class="d-flex flex-wrap justify-content-xl-between" style="padding: 20px">
                  <form class="forms-sample w-100" method="POST" action="">
                    <div class="form-group row">
                      <!-- Kolom email (berdasar uid (users) session) -->
                      <label for="email" class="col-sm-3 col-form-label">Email (Terverifikasi)</label>
                      <div class="col-sm-9">
                        <input type="email" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="email" name="email" value="{{ $dataMitraData['email'] ?? 'Email tidak ditemukan' }}" placeholder="E-Mail toko terverifikasi" readonly>
                      </div>

                      <!-- Kolom nama owner (berdasar uid (users) session) -->
                      <label for="owner" class="col-sm-3 col-form-label">Nama Owner</label>
                      <div class="col-sm-9">
                        <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="owner" name="owner" value="{{ $dataMitraData['namaLengkap'] ?? 'Nama lengkap tidak ditemukan' }}" placeholder="Nama owner toko" readonly>
                      </div>

                      <!-- Kolom nama owner (berdasar uid (users) session) -->
                      <label for="nama_toko" class="col-sm-3 col-form-label">Nama Toko</label>
                      <div class="col-sm-9">
                        <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="nama_toko" name="nama_toko" value="{{ $dataMitraProfile['namaToko'] ?? 'Nama toko tidak ditemukan' }}" placeholder="Nama toko" readonly>
                      </div>

                      <!-- Kolom alamat toko -->
                      <label for="alamat_toko" class="col-sm-3 col-form-label">Alamat Toko</label>
                      <div class="col-sm-9">
                        <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="alamat_toko" name="alamat_toko" value="{{ $dataMitraProfile['alamatToko'] ?? 'Alamat toko tidak ditemukan' }}" placeholder="Nama toko" readonly>
                      </div>

                      <!-- Kolom Phone -->
                      <label for="phone" class="col-sm-3 col-form-label">Nomor Telepon</label>
                      <div class="col-sm-9">
                        <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="phone" name="phone" value="{{ $dataMitraProfile['noTelp'] ?? 'Nomor telepon tidak ditemukan' }}" placeholder="Nomor telepon tidak ditemukan" readonly>
                      </div>

                      <!-- Kolom alamat toko -->
                      <label for="role" class="col-sm-3 col-form-label">Role</label>
                      <div class="col-sm-9">
                        <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="role" name="role" value="{{ $dataMitraRole ?? 'Role pengguna tidak ditemukan '}}" placeholder="Role" readonly>
                      </div>

                      <!-- Kolom Auth Method (berdasar uid (users) session)-->
                      <label for="auth_method" class="col-sm-3 col-form-label">Metode Autentikasi</label>
                      <div class="col-sm-9">
                        <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="auth_method" name="auth_method" value="{{ $dataMitraData['authMethod'] }}" placeholder="Metode autentikasi" readonly>
                      </div>
                    </div>
                  </form>
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

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Content Management</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/base/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('css/alerts/style-floating.css') }}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('pengunjung/images/sascode-logo.jpg') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body style="color: white; font-family: Arial, Helvetica, sans-serif;">
  <div class="container-scroller">

    @if(session('error'))
      <div class="floating-alert-error" id="statusAlert" style="font-size: 14px;">
        <div class="alert-content">
          {{ session('error') }}
        </div>
      </div>
    @endif
    
    @if(session('success'))
      <div class="floating-alert-success" id="statusAlert" style="font-size: 14px;">
        <div class="alert-content">
          {{ session('success') }}
        </div>
      </div>
    @endif

    @if ($errors->any())
      <div class="floating-alert-error" id="statusAlert" style="font-size: 14px;">
        <div class="alert-content">
          <ul style="margin: 0; padding-left: 20px; text-align: left;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif

    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
        <div class="row flex-grow">
          <div class="col-lg-6 d-flex align-items-center justify-content-center" style="background-color: #030405">
            <div class="auth-form-transparent text-left p-3">
              <div class="brand-logo" style="align-items: center;">
                <h2 style="text-align: center; margin-top: 30px; margin-bottom: 13px">Reset Password</h2>
                <p style="font-size:12px; text-align: center; margin-top: 13xp; margin-bottom: 50px;">Silakan isi data sebelum melanjutkan!</p>
                  <p style="font-size: 10px; text-align: center; margin-top: 20px;">Klik link di bawah ini untuk mereset password akun Anda: <a href="{{ $link }}" style="color: #8C3061; text-decoration:none">{{ $link }}</a></p>
                  <p style="font-size: 10px; text-align: center;">Jika Anda tidak meminta reset, abaikan email ini. <a href="{{ url('/masuk') }}" style="color: #8C3061; text-decoration:none"> Masuk</a></p>
              </div>
            </div>
          </div>
          <div class="col-lg-6 login-half-bg d-flex flex-row">
            <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; 2018  All rights reserved.</p>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script>
    window.addEventListener('DOMContentLoaded', () => {
      const alertBox = document.getElementById('statusAlert');
      if (alertBox) {
        alertBox.style.display = 'block';
        setTimeout(() => {
          alertBox.style.display = 'none';
        }, 4000); // 4 detik
      }
    });
  </script>
  <script src="{{ asset('vendors/base/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{ asset('js/off-canvas.js') }}"></script>
  <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('js/template.js') }}"></script>
  <!-- endinject -->
</body>

</html>

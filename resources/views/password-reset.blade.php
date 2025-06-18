<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Reset Password</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/base/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('css/alerts/style-floating.css') }}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <script src="{{ asset('pengunjung/js/pace.min.js') }}"></script>

  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('pengunjung/images/sascode-logo.jpg') }}">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body style="color: white; font-family: Poppins;">
  <div class="container-scroller">

    <div id="preloader">
      <div id="loader" class="dots-jump">
          <div></div>
          <div></div>
          <div></div>
      </div>
    </div>

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
                <p style="font-size:12px; text-align: center; margin-top: 13xp; margin-bottom: 50px;">Masukkan email Anda yang terdaftar. Instruksi konfirmasi akan dikirim ke alamat email Anda.</p>
                <form action="{{ route('password-reset') }}" method="POST">
                  @csrf
                  <div class="form-group">
                      <i class="mdi mdi-account-check" style="margin-right:6px; margin-left:6px"></i>
                      <label>E-Mail</label>
                      <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Masukkan E-Mail" style="border-radius: 18px; color:white;" required>
                      @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                  </div>
                  <button type="submit" class="btn btn-primary btn-block" style="margin-top:30px; background: #890B3F; border:none; outline:none; box-shadow: none; border-radius: 15px;">Kirim link</button>
                  <p style="font-size: 10px; text-align: center; margin-top: 20px;">Terdapat kendala saat mencoba masuk? <a href="https://wa.me/6289696210706" style="color: #890B3F; text-decoration:none">Hubungi Kami</a></p>
                  <p style="font-size: 10px; text-align: center;">Atau <a href="{{ url('/masuk') }}" style="color: #890B3F; text-decoration:none"> Masuk</a></p>
                </form>
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
  <script src="{{ asset('pengunjung/js/main.js') }}"></script>
  <!-- endinject -->
</body>

</html>

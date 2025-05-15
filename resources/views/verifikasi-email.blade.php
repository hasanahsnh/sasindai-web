<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Registrasi Berhasil! Cek E-Mail</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/base/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
</head>

<body>
      <!-- partial -->      
        <div class="content-wrapper">
          <section class="py-6">
            <div class="error-container" style="text-align: center">
              <h4>Registrasi Berhasil dan Email Verifikasi Telah Dikirim</h4>
              <p>Silakan cek kotak masuk atau spam untuk verifikasi email Anda.</p>
            </div>

            <div class="resend-section" style="text-align: center; margin-top: 20px;">
              <p>Anda dapat mengirim ulang email verifikasi dalam <span id="countdown">3:00</span></p>
              <form id="resend-form" action="{{ url('/kirim-ulang-verifikasi') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="email" value="{{ old('email', request()->email) }}">
                <button type="submit" class="btn btn-primary">Kirim Ulang Email Verifikasi</button>
              </form>
            </div>
          </section>
        </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script>
    let duration = 180; // 3 minutes in seconds
    const countdownEl = document.getElementById('countdown');
    const resendForm = document.getElementById('resend-form');
  
    const timer = setInterval(() => {
      const minutes = Math.floor(duration / 60);
      const seconds = duration % 60;
      countdownEl.textContent = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
      duration--;
  
      if (duration < 0) {
        clearInterval(timer);
        countdownEl.textContent = '0:00';
        resendForm.style.display = 'inline-block';
      }
    }, 1000);
  </script>

  <script src="{{ asset('vendors/base/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{ asset('js/off-canvas.js') }}"></script>
  <script src="{{ asset('js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('js/template.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('js/file-upload.js') }}"></script>
  <!-- End custom js for this page-->
</body>

</html>

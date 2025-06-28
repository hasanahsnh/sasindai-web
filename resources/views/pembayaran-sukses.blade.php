<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Masuk - Sasindai by Thiesa</title>
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
  <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>

</head>

<body style="color: white; font-family: Poppins;" id="top">
  <div class="container-scroller">

    <div id="preloader">
      <div id="loader" class="dots-jump">
          <div></div>
          <div></div>
          <div></div>
      </div>
    </div>
    <div class="auth-form-transparent text-center p-3" style="background-color: #ffffff; text-align: center;">
        <div class="brand-logo" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <dotlottie-player
            src="https://lottie.host/20afbef4-ebed-40f7-b50a-bad4343f20c4/luCn1dbmuN.lottie"
            background="transparent"
            speed="1"
            style="width: 250px; height: 250px"
            loop
            autoplay
            ></dotlottie-player>
            <h1 style="margin-top: 50px; color: #8D0B41;">Pembayaran Berhasil</h1>
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

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>FAQ</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/base/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/faq/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('pengunjung/images/sascode-logo.jpg') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Modal -->
  <!-- Accordion -->
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
          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <h1 class="card-title" style="font-size:16px; color:black; display: inline-block; border-bottom: 2px solid #522258; padding-bottom: 10px;">
                    FAQ
                  </h1>
                </div>

                <div class="col-12 grid-margin stretch-card">
                  <div class="faq-section">
                    <div class="faq-item">
                      <div class="faq-question">
                        <span>Apa saja tugas seorang Konten Manajer?</span>
                        <span class="arrow">▼</span>
                      </div>
                      <div class="faq-answer">
                        <p>This FAQ provides answers to the most common questions about our service.</p>
                      </div>
                    </div>
                  
                    <div class="faq-item">
                      <div class="faq-question">
                        <span>Bagaimana cara menguploud data?</span>
                        <span class="arrow">▼</span>
                      </div>
                      <div class="faq-answer">
                        <p>You can contact support through our contact page or by sending an email to support@example.com.</p>
                      </div>
                    </div>
                  
                    <div class="faq-item">
                      <div class="faq-question">
                        <span>Bagaimana cara mengirimkan data motif ke setiap Mitra?</span>
                        <span class="arrow">▼</span>
                      </div>
                      <div class="faq-answer">
                        <p>Yes, we offer a 14-day free trial for all new users. Sign up to get started.</p>
                      </div>
                    </div>

                    <div class="faq-item">
                      <div class="faq-question">
                        <span>Bagaimana cara menghubungi Tim Teknis jika terjadi masalah?</span>
                        <span class="arrow">▼</span>
                      </div>
                      <div class="faq-answer">
                        <p>Yes, we offer a 14-day free trial for all new users. Sign up to get started.</p>
                      </div>
                    </div>
                  </div>
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
  <!-- Accordion js -->
  <script>
    // Menambahkan event listener ke semua pertanyaan FAQ
    document.querySelectorAll('.faq-question').forEach(question => {
      question.addEventListener('click', () => {
        const answer = question.nextElementSibling;
        const arrow = question.querySelector('.arrow');
  
        // Toggle class "open" untuk jawaban dan panah
        answer.classList.toggle('open');
        arrow.classList.toggle('open');
  
        // Tutup semua FAQ lain jika dibuka
        document.querySelectorAll('.faq-answer').forEach(otherAnswer => {
          if (otherAnswer !== answer) {
            otherAnswer.classList.remove('open');
          }
        });
        document.querySelectorAll('.arrow').forEach(otherArrow => {
          if (otherArrow !== arrow) {
            otherArrow.classList.remove('open');
          }
        });
      });
    });
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

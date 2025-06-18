<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Level Pengguna</title>
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

          <!-- Validasi terhadap firebase -->
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

          <!-- Validasi max digit -->
          @if ($errors->any())
          <div class="custom-alert-error">
            @foreach ($errors->all() as $error)
              <span class="alert-icon-error">!</span>
              <span class="alert-message-success">{{ $error }}</span>
              <button class="alert-close-error" onclick="this.parentElement.style.display='none';">&times;</button>
            @endforeach
          </div>    
          @endif

          <!-- Mulai proses -->
          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <h1 class="card-title" style="font-size:16px; color:black; display: inline-block; border-bottom: 2px solid #522258; padding-bottom: 10px;">
                    Level Pengguna
                  </h1>
                  
                  <!-- Dropdown menu layar lebar 
                    <div class="d-none d-md-flex ms-3" style="margin-left: 60px;">
                    <a type="button" data-toggle="modal" data-target="#modalTambah" onclick=""
                    style="font-size:14px; margin-right: 30px; color:#2768FF; display: flex; align-items: center;">
                      <i class="mdi mdi-plus-box" style="font-size: 20px; vertical-align: middle; margin-right: 5px; color:#2768FF"></i>
                        TAMBAH ROLE
                    </a>
                    <a href="" type="button"
                    style="font-size:14px; color:#757575; display: flex; align-items: center; text-decoration: none;">
                      <i class="mdi mdi-download text-muted" style="font-size: 20px; vertical-align: middle; margin-right: 5px;"></i>
                        UNDUH DATA ROLE
                    </a>
                  </div> -->

                  <!-- Dropdown menu untuk tampilan layar yang lebih kecil
                  <div class="ms-3 d-md-none">
                    <div class="dropdown">
                      <a class="mdi mdi-dots-vertical" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                      style="font-size: 20px; margin-left:20px">
                      </a>
                      <div id="dropdownMenu" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" type="button" data-toggle="modal" data-target="#modalTambah" onclick="">
                              <i class="mdi mdi-plus-box" style="font-size: 20px; vertical-align: middle; margin-right: 5px; color:#2768FF"></i>
                              TAMBAH ROLE
                          </a>
                          <a href="" class="dropdown-item" type="button" style="font-size:14px; color:#757575; display: flex; align-items: center; text-decoration: none;">
                              <i class="mdi mdi-download text-muted" style="font-size: 20px; vertical-align: middle; margin-right: 5px;"></i>
                              UNDUH DATA ROLE
                          </a>
                      </div>
                    </div>
                  </div> -->
               
                </div>

                <div class="table-responsive">
                  <table class="table table-hover w-100">
                    <thead>
                      <tr>
                        <th>ID Level</th>
                        <th>Level</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- tr -->
                      @if($roles && count($roles) > 0)
                        @foreach ($roles as $item)
                        @if(!empty($item['idRole']) || !empty($item['role']))
                          <tr>
                            <td>{{ $item['idRole'] }}</td>
                            <td>{{ $item['role'] }}</td>
                          </tr>

                        @endif
                        @endforeach
                      @else
                        <tr>
                          <td colspan="5" style="text-align: center">Data role tidak ditemukan</td>
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
                        
                        <div class="main-content text-center">
                            
                            <a href="#" class="close-btn" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><span class="icon-close2"></span></span>
                              </a>
                            <div class="card-body">
                              <h4 class="card-title">Tambah role</h4>
                              <form class="forms-sample" id="formTambah" action="{{ route('tambah-role') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                  <label for="inputIdRole" class="col-sm-3 col-form-label">ID Role</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" id="id_role" name="id_role" placeholder="ID Role" required>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="inputRole" class="col-sm-3 col-form-label">Role</label>
                                  <div class="col-sm-9">
                                    <input type="text" class="form-control" id="role" name="role" placeholder="Role" required>
                                  </div>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                <button class="btn btn-light" data-dismiss="modal" onclick="clearForm('formTambah')">Cancel</button>
                              </form>
                            </div>
                          
                        </div>

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

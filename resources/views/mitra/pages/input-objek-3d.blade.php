<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Objek 3D</title>
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
                <div class="d-flex align-items-center mb-3">
                  <h1 class="card-title" style="font-size:16px; color:black; display: inline-block; border-bottom: 2px solid #8D0B41; padding-bottom: 10px;">
                    DATA MODEL 3D
                  </h1>
                  <div class="d-none d-md-flex ms-3" style="margin-left: 60px;">
                    <a type="button" data-toggle="modal" data-target="#modalTambah" onclick="setRedirectUrl('{{ route('tambah-berita') }}')"
                    style="font-size:14px; margin-right: 30px; color:#8D0B41; display: flex; align-items: center;">
                      <i class="mdi mdi-plus-box" style="font-size: 20px; vertical-align: middle; margin-right: 5px; color:#8D0B41"></i>
                        TAMBAH MODEL 3D
                    </a>
                  </div>

                  <!-- Dropdown Menu for smaller screens -->
                  <div class="ms-3 d-md-none">
                    <div class="dropdown">
                      <a class="mdi mdi-dots-vertical" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                      style="font-size: 20px; margin-left:20px">
                      </a>
                      <div id="dropdownMenu" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" type="button" data-toggle="modal" data-target="#modalTambah" onclick="setRedirectUrl('{{ route('tambah-berita') }}')">
                              <i class="mdi mdi-plus-box" style="font-size: 20px; vertical-align: middle; margin-right: 5px; color:#8D0B41"></i>
                              TAMBAH MODEL 3D
                          </a>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-hover w-100">
                    <thead>
                      <tr>
                        <th>Nama Model</th>
                        <th>Deskripsi</th>
                        <th>File .glb</th>
                        <th>Preview</th>
                        <th>Dibuat pada</th>
                        <th>Diperbarui pada</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- tr fields -->
                      @if($objek3d && count($objek3d) > 0)
                        @foreach ($objek3d as $key => $item)
                        @if(!empty($item['namaObjek']) || !empty($item['deskripsiObjek']) || !empty($item['glbUrl']) || !empty($item['createdAt']))
                          <tr>
                            <td>{{ $item['namaObjek'] }}</td>
                            <td>{{ $item['deskripsiObjek'] ?? 'Deskripsi tidak ditemukan' }}</td>
                            <td>
                              <a href="{{ $item['glbUrl'] }}" target="_blank" title="Unduh foto berita">{{ $item['glbUrl'] }}</a>
                            </td>
                            <td>
                              <a href="{{ $item['previewObjek'] }}" target="_blank" title="Unduh foto berita">{{ $item['previewObjek'] }}</a>
                            </td>
                            <td>{{ $item['createdAt'] ?? 'Tanggal tidak ditemukan' }}</td>
                            <td>{{ $item['updateAt'] ?? 'Tanggal tidak ditemukan' }}</td>
                            <td>
                              <a href="" data-toggle="modal" data-target="#modalEdit{{ $key }}" title="Edit"><i class="fas fa-edit" style="margin-right: 15px;"></i></a>    
                              <a href="" data-toggle="modal" data-target="#modalHapus{{ $key }}" title="Hapus"><i class="fa-solid fa-trash" style="color: red"></i></a>     
                            </td>
                          </tr>

                          <!-- Modal Hapus -->
                          <div class="modal fade" id="modalHapus{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 700px; width: 100%;">
                              <div class="modal-content rounded-0">
                                <div class="modal-body p-4 px-5">
                        
                                  
                                  <div class="main-content text-center">
                                      
                                      <a href="#" class="close-btn" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true"><span class="icon-close2"></span></span>
                                        </a>
                                      <div class="card-body">
                                        <h4 class="card-title">Hapus Model</h4>
                                        <p class="card-description">
                                          <i class="fas fa-exclamation-triangle" style="color: red;"></i>
                                          Anda yakin ingin menghapus data model yang dipilih?
                                        </p>
                                        <form class="forms-sample" id="formHapus{{ $key }}" action="" method="POST">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="btn btn-danger mr-2">Hapus</button>
                                          <button class="btn btn-light" data-dismiss="modal" onclick="clearForm('formHapus{{ $key }}')">Cancel</button>
                                        </form>
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                        @endif
                        @endforeach
                      @else
                        <tr>
                          <td colspan="6" style="text-align: center">Data Model tidak ditemukan</td>
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
                              <h4 class="card-title">Tambah Model</h4>
                              <form class="forms-sample" id="formTambah" action="{{ route('simpan.objek.3d') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                  <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nama Model</label>
                                  <div class="col-sm-9">
                                    <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="nama_objek" name="nama_objek" placeholder="Nama Objek" required>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Deskripsi</label>
                                  <div class="col-sm-9">
                                    <textarea class="form-control" style="border: 1px solid #8D0B41; border-radius: 4px;" id="deskripsi_objek" name="deskripsi_objek" rows="4" placeholder="Deskripsi Objek" required></textarea>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="exampleInputMobile" class="col-sm-3 col-form-label">Unggah File .glb</label>
                                  <div class="col-sm-9">
                                    <input type="file" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="file_objek" name="file_objek" placeholder="Pilih File .gbl" required>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="exampleInputMobile" class="col-sm-3 col-form-label">Unggah Gambar Preview</label>
                                  <div class="col-sm-9">
                                    <input type="file" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="preview_objek" name="preview_objek" placeholder="Pilih Foto" required>
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
          @include('mitra.partials._footer')
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

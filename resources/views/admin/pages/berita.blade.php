<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Rilis Media</title>
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
                    Rilis media
                  </h1>
                  <div class="d-none d-md-flex ms-3" style="margin-left: 60px;">
                    <a type="button" data-toggle="modal" data-target="#modalTambah" onclick="setRedirectUrl('{{ route('tambah-berita') }}')"
                    style="font-size:14px; margin-right: 30px; color:#8D0B41; display: flex; align-items: center;">
                      <i class="mdi mdi-plus-box" style="font-size: 20px; vertical-align: middle; margin-right: 5px; color:#8D0B41"></i>
                        TAMBAH BERITA
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
                              TAMBAH BERITA
                          </a>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-hover w-100">
                    <thead>
                      <tr>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Konten</th>
                        <th>Tanggal Terbit</th>
                        <th>Foto Berita</th>
                        <th>Dibuat pada</th>
                        <th>Diperbarui pada</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- tr fields -->
                      @if($beritas && count($beritas) > 0)
                        @foreach ($beritas as $key => $item)
                        @if(!empty($item['judulArtikel']) || !empty($item['jurnalis']) || !empty($item['kontenBerita']) || !empty($item['tanggalTerbit']) || !empty($item['fotoBeritaUrl']))
                          <tr>
                            <td>{{ $item['judulArtikel'] }}</td>
                            <td>{{ $item['jurnalis'] }}</td>
                            <td>{{ $item['kontenBerita'] }}</td>
                            <td>{{ $item['tanggalTerbit'] }}</td>
                            <td>
                              <a href="{{ $item['fotoBeritaUrl'] }}" target="_blank" title="Unduh foto berita">{{ $item['fotoBeritaUrl'] }}</a>
                            </td>
                            <td>{{ $item['createAt']  ?? 'Tanggal tidak ditemukan' }}</td>
                            <td>{{ $item['updateAt']  ?? 'Tanggal tidak ditemukan' }}</td>
                            <td>
                              <a href="" data-toggle="modal" data-target="#modalEdit{{ $key }}" title="Edit"><i class="fas fa-edit" style="margin-right: 15px;"></i></a>    
                              <a href="" data-toggle="modal" data-target="#modalHapus{{ $key }}" title="Hapus"><i class="fa-solid fa-trash" style="color: red"></i></a>     
                            </td>
                          </tr>

                          <!-- Modal Edit -->
                          <div class="modal fade" id="modalEdit{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 700px; width: 100%;">
                              <div class="modal-content rounded-0">
                                <div class="modal-body p-4 px-5">
                        
                                  
                                  <div class="main-content text-center">
                                      
                                      <a href="#" class="close-btn" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true"><span class="icon-close2"></span></span>
                                        </a>
                                      <div class="card-body">
                                        <h4 class="card-title">Edit Berita</h4>
                                        <form class="forms-sample" id="formEdit{{ $key }}" action="{{ route('update-berita', ['id' => $key]) }}" method="POST" enctype="multipart/form-data">
                                          @csrf
                                          @method('PUT')
                                          <div class="form-group row">
                                            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Judul Artikel</label>
                                            <div class="col-sm-9">
                                              <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" value="{{ $item['judulArtikel'] }}" id="judul_artikel" name="judul_artikel" placeholder="Judul artikel" required>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Jurnalis</label>
                                            <div class="col-sm-9">
                                              <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" value="{{ $item['jurnalis'] }}" id="jurnalis" name="jurnalis" placeholder="Jurnalis" required>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Konten Berita</label>
                                            <div class="col-sm-9">
                                              <textarea class="form-control" style="border: 1px solid #8D0B41; border-radius: 4px;" id="konten_berita" name="konten_berita" placeholder="Konten berita" required>{{ $item['kontenBerita'] }}</textarea>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Foto Berita</label>
                                            <div class="col-sm-9">
                                              <input type="file" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" value="{{ $item['fotoBeritaUrl'] }}" id="foto_berita" name="foto_berita" placeholder="Pilih foto">
                                            </div>
                                          </div>
                                          <button type="submit" class="btn btn-primary mr-2">Update</button>
                                          <button class="btn btn-light" data-dismiss="modal" onclick="clearForm('formEdit{{ $key }}')">Cancel</button>
                                        </form>
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

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
                                        <h4 class="card-title">Hapus Rilis Media</h4>
                                        <p class="card-description">
                                          <i class="fas fa-exclamation-triangle" style="color: red;"></i>
                                          Anda yakin ingin menghapus data rilis media yang dipilih?
                                        </p>
                                        <form class="forms-sample" id="formHapus{{ $key }}" action="{{ route('destroy-berita', ['id' => $key]) }}" method="POST">
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
                          <td colspan="9" style="text-align: center">Rilis media tidak ditemukan</td>
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
                              <h4 class="card-title">Tambah Berita</h4>
                              <form class="forms-sample" id="formTambah" action="{{ url('simpan-berita') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                  <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Judul</label>
                                  <div class="col-sm-9">
                                    <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="judul_artikel" name="judul_artikel" placeholder="Judul artikel" required>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Penulis</label>
                                  <div class="col-sm-9">
                                    <input type="text" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="jurnalis" name="jurnalis" placeholder="Jurnalis" required>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Konten</label>
                                  <div class="col-sm-9">
                                    <textarea class="form-control" style="border: 1px solid #8D0B41; border-radius: 4px;" id="konten_berita" name="konten_berita" rows="4" placeholder="Konten berita" required></textarea>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="exampleInputMobile" class="col-sm-3 col-form-label">Foto</label>
                                  <div class="col-sm-9">
                                    <input type="file" style="border: 1px solid #8D0B41; border-radius: 4px;" class="form-control" id="foto_berita" name="foto_berita" placeholder="Pilih Foto" required>
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

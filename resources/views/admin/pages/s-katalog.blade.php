<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>S-Katalog</title>
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
                    DATA S-KATALOG
                  </h1>
                  <div class="d-none d-md-flex ms-3" style="margin-left: 60px;">
                    <a type="button" data-toggle="modal" data-target="#modalTambah" onclick="setRedirectUrl('{{ route('tambah-motif') }}')"
                    style="font-size:14px; margin-right: 30px; color:#8D0B41; display: flex; align-items: center;">
                      <i class="mdi mdi-plus-box" style="font-size: 20px; vertical-align: middle; margin-right: 5px; color:#8D0B41"></i>
                        TAMBAH MOTIF
                    </a>
                    <a href="{{ route('download-data-katalog') }}" type="button"
                    style="font-size:14px; color:#757575; display: flex; align-items: center; text-decoration: none;">
                      <i class="mdi mdi-download text-muted" style="font-size: 20px; vertical-align: middle; margin-right: 5px;"></i>
                        UNDUH DATA
                    </a>
                  </div>

                  <!-- Dropdown Menu for smaller screens -->
                  <div class="ms-3 d-md-none">
                    <div class="dropdown">
                      <a class="mdi mdi-dots-vertical" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                      style="font-size: 20px; margin-left:20px">
                      </a>
                      <div id="dropdownMenu" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" type="button" data-toggle="modal" data-target="#modalTambah" onclick="setRedirectUrl('{{ route('tambah-motif') }}')">
                              <i class="mdi mdi-plus-box" style="font-size: 20px; vertical-align: middle; margin-right: 5px; color:#8D0B41"></i>
                              TAMBAH MOTIF
                          </a>
                          <a href="{{ route('download-data-katalog') }}" class="dropdown-item" type="button" style="font-size:14px; color:#757575; display: flex; align-items: center; text-decoration: none;">
                              <i class="mdi mdi-download text-muted" style="font-size: 20px; vertical-align: middle; margin-right: 5px;"></i>
                              UNDUH DATA
                          </a>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-hover w-100">
                    <thead>
                      <tr>
                        <th>Lihat</th>
                        <th>Nama Motif</th>
                        <th>Arti Motif</th>
                        <th>Sumber Arti Motif</th>
                        <th>Gambar Motif</th>
                        <th>Sumber Gambar Motif</th>
                        <th>QR Code Motif</th>
                        <th>Tindakan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- tr fields -->
                      @if($katalogs && count($katalogs) > 0)
                        @foreach ($katalogs as $key => $item)
                        @if(!empty($item['motif']) || !empty($item['filosofi']) || !empty($item['sumber']) || !empty($item['gambarUrl']))
                          <tr>
                            <td>
                              <a href="" data-toggle="modal" data-target="#modalLihat{{ $key }}" title="Detail"><i class="mdi mdi-eye" style="margin-right: 15px;"></i></a>    
                            </td>
                            <td>{{ $item['motif'] }}</td>
                            <td>{{ $item['filosofi'] }}</td>
                            <td>{{ $item['sumberFilosofi'] }}</td>
                            <td>
                              <a href="{{ $item['gambarUrl'] }}" target="_blank" title="Unduh gambar motif">{{ $item['gambarUrl'] }}</a>
                            </td>
                            <td>{{ $item['sumberGambar'] }}</td>
                            <td>
                              <a href="{{ $item['qrCodeUrl'] }}" target="_blank" title="Unduh QR Code Motif">{{ $item['qrCodeUrl'] }}</a>
                            </td>
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
                                        <h4 class="card-title">Edit Motif</h4>
                                        <p class="card-description">
                                          <i class="fas fa-exclamation-triangle" style="color: red;"></i>
                                          Isi semua field, dan sertakan sumber filosofi yang valid!
                                        </p>
                                        <form class="forms-sample" id="formEdit{{ $key }}" action="{{ route('update-motif', ['id' => $key]) }}" method="POST" enctype="multipart/form-data">
                                          @csrf
                                          @method('PUT')
                                          <div class="form-group row">
                                            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nama Motif</label>
                                            <div class="col-sm-9">
                                              <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" class="form-control" value="{{ $item['motif'] }}" id="nama_motif" name="nama_motif" placeholder="Nama motif" required>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Filosofi Motif</label>
                                            <div class="col-sm-9">
                                              <textarea class="form-control" style="border: 2px solid #8D0B41; border-radius: 4px;" id="filosofi_motif" name="filosofi_motif" placeholder="Filosofi motif" required>{{ $item['filosofi'] }}</textarea>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Sumber Filosofi</label>
                                            <div class="col-sm-9">
                                              <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" class="form-control" value="{{ $item['sumberFilosofi'] }}" id="sumber_filosofi" name="sumber_filosofi" placeholder="Mobile number" required>
                                              <div class="note" style="color: gray; font-size:11px; padding-top: 5px;">
                                                <strong>Format penulisan sumber:</strong> [Jurnal/situs]
                                              </div>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Gambar Motif</label>
                                            <div class="col-sm-9">
                                              <input type="file" style="border: 2px solid #8D0B41; border-radius: 4px;" class="form-control" value="{{ $item['gambarUrl'] }}" id="gambar_motif" name="gambar_motif" placeholder="Pilih gambar">
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Sumber Filosofi</label>
                                            <div class="col-sm-9">
                                              <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" class="form-control" value="{{ $item['sumberGambar'] }}" id="sumber_gambar" name="sumber_gambar" placeholder="Mobile number" required>
                                              <div class="note" style="color: gray; font-size:11px; padding-top: 5px;">
                                                <strong>Format penulisan sumber:</strong> [Jurnal/situs]
                                              </div>
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
                                        <h4 class="card-title">Hapus Motif</h4>
                                        <p class="card-description">
                                          <i class="fas fa-exclamation-triangle" style="color: red;"></i>
                                          Anda yakin ingin menghapus data motif yang dipilih?
                                        </p>
                                        <form class="forms-sample" id="formHapus{{ $key }}" action="{{ route('destroy-motif', ['id' => $key]) }}" method="POST">
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

                          <!-- Modal Lihat -->
                          <div class="modal fade" id="modalLihat{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 700px; width: 100%;">
                              <div class="modal-content rounded-0">
                                <div class="modal-body p-4 px-5">
                        
                                  
                                  <div class="main-content text-center">
                                      
                                      <a href="#" class="close-btn" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true"><span class="icon-close2"></span></span>
                                        </a>
                                      <div class="card-body">
                                        <h4 class="card-title">Motif {{ $item['motif'] }}</h4>
                                        <form class="forms-sample" id="formEdit{{ $key }}" action="" method="">
                                          @csrf
                                          <div class="form-group row">
                                            <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nama Motif</label>
                                            <div class="col-sm-9">
                                              <input type="text" class="form-control" value="{{ $item['motif'] }}" id="nama_motif" name="nama_motif" placeholder="Nama motif" readonly>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Filosofi Motif</label>
                                            <div class="col-sm-9">
                                              <textarea class="form-control" id="filosofi_motif" name="filosofi_motif" placeholder="Filosofi motif" readonly>{{ $item['filosofi'] }}</textarea>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Sumber Filosofi</label>
                                            <div class="col-sm-9">
                                              <input type="text" class="form-control" value="{{ $item['sumberFilosofi'] }}" id="sumber_filosofi" name="sumber_filosofi" readonly>
                                              <div class="note" style="color: gray; font-size:11px; padding-top: 5px;">
                                                <strong>Format penulisan sumber:</strong> [Jurnal/situs]
                                              </div>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Gambar Motif</label>
                                            <div class="col-sm-9">
                                              <img src="{{ $item['gambarUrl'] }}" alt="Preview Foto Toko" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">Sumber Filosofi</label>
                                            <div class="col-sm-9">
                                              <input type="text" class="form-control" value="{{ $item['sumberGambar'] }}" id="sumber_gambar" name="sumber_gambar" readonly>
                                              <div class="note" style="color: gray; font-size:11px; padding-top: 5px;">
                                                <strong>Format penulisan sumber:</strong> [Jurnal/situs]
                                              </div>
                                            </div>
                                          </div>
                                          <div class="form-group row">
                                            <label for="exampleInputMobile" class="col-sm-3 col-form-label">QR Code Motif</label>
                                            <div class="col-sm-9">
                                              <img src="{{ $item['qrCodeUrl'] }}" alt="Preview QR Code Motif" style="max-width: 50%; height: auto; margin-bottom: 10px;">
                                            </div>
                                          </div>
                                          <button class="btn btn-light" data-dismiss="modal">Cancel</button>
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
                          <td colspan="8" style="text-align: center">Data motif tidak ditemukan</td>
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
                              <h4 class="card-title">Tambah Motif</h4>
                              <p class="card-description">
                                <i class="fas fa-exclamation-triangle" style="color: red;"></i>
                                  Isi semua field, dan sertakan sumber filosofi yang valid!
                              </p>
                              <form class="forms-sample" id="formTambah" action="{{ url('simpan-motif') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                  <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Nama Motif</label>
                                  <div class="col-sm-9">
                                    <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" class="form-control" id="nama_motif" name="nama_motif" placeholder="Nama motif" required>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="exampleInputEmail2" class="col-sm-3 col-form-label">Arti Motif</label>
                                  <div class="col-sm-9">
                                    <textarea class="form-control" style="border: 2px solid #8D0B41; border-radius: 4px;" id="filosofi_motif" name="filosofi_motif" rows="4" placeholder="Filosofi motif" required></textarea>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="exampleInputMobile" class="col-sm-3 col-form-label">Sumber Arti Motif</label>
                                  <div class="col-sm-9">
                                    <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" class="form-control" id="sumber_filosofi" name="sumber_filosofi" placeholder="Sumber filosofi" required>
                                    <div class="note" style="color: gray; font-size:11px; padding-top: 5px;">
                                      <strong>Format penulisan sumber:</strong> [Jurnal/Situs]
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="exampleInputMobile" class="col-sm-3 col-form-label">Gambar Motif</label>
                                  <div class="col-sm-9">
                                    <input type="file" style="border: 2px solid #8D0B41; border-radius: 4px;" class="form-control" id="gambar_motif" name="gambar_motif" placeholder="Pilih gambar" required>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label for="exampleInputMobile" class="col-sm-3 col-form-label">Sumber Gambar Motif</label>
                                  <div class="col-sm-9">
                                    <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" class="form-control" id="sumber_gambar" name="sumber_gambar" placeholder="Sumber gambar motif" required>
                                    <div class="note" style="color: gray; font-size:11px; padding-top: 5px;">
                                      <strong>Format penulisan sumber:</strong> [Jurnal/Situs]
                                    </div>
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

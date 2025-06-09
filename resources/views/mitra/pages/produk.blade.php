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
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('pengunjung/images/sascode-logo.jpg') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                    DATA PRODUK
                  </h1>
                  <div class="d-none d-md-flex ms-3" style="margin-left: 60px;">
                    <a type="button" data-toggle="modal" data-target="#modalTambah" onclick=""
                    style="font-size:14px; margin-right: 30px; color:#8D0B41; display: flex; align-items: center;">
                      <i class="mdi mdi-plus-box" style="font-size: 20px; vertical-align: middle; margin-right: 5px; color:#8D0B41"></i>
                        TAMBAH PRODUK
                    </a>
                    <!--<a href="" type="button"
                    style="font-size:14px; color:#757575; display: flex; align-items: center; text-decoration: none;">
                      <i class="mdi mdi-download text-muted" style="font-size: 20px; vertical-align: middle; margin-right: 5px;"></i>
                        UNDUH DATA
                    </a>-->
                  </div>

                  <!-- Dropdown Menu for smaller screens -->
                  <div class="ms-3 d-md-none">
                    <div class="dropdown">
                      <a class="mdi mdi-dots-vertical" href="#" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                      style="font-size: 20px; margin-left:20px">
                      </a>
                      <div id="dropdownMenu" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" type="button" data-toggle="modal" data-target="#modalTambah" onclick="">
                              <i class="mdi mdi-plus-box" style="font-size: 20px; vertical-align: middle; margin-right: 5px; color:#8D0B41"></i>
                              TAMBAH PRODUK
                          </a>
                          <!--<a href="" class="dropdown-item" type="button" style="font-size:14px; color:#757575; display: flex; align-items: center; text-decoration: none;">
                              <i class="mdi mdi-download text-muted" style="font-size: 20px; vertical-align: middle; margin-right: 5px;"></i>
                              UNDUH DATA
                          </a>-->
                      </div>
                    </div>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-hover w-100">
                    <thead>
                      <tr>
                        <th>ID Produk</th> <!-- Auto generate -->
                        <th>Nama Produk</th>
                        <th>URL Foto Produk</th>
                        <th>Deksripsi</th>
                        <th>Lihat Varian Dijual</th>
                        <th>Terjual</th>
                        <th>Sisa Stok</th>
                        <th>Ditambah pada</th> <!-- dari tabel kategori ?? set null dulu-->
                        <th>Diperbarui pada</th>
                        <th>Aksi</th> <!-- merujuk ke tabel komentar (one to many) ke tabel produk ?? set null -->
                      </tr>
                    </thead>
                    <tbody>
                      <!-- tr fields -->
                      @if($produks && count($produks) > 0)
                        @foreach ($produks as $key => $item)
                        @if(
                          !empty($item['idProduk']) ||
                          !empty($item['namaProduk']) ||
                          !empty($item['urlFotoProduk']) ||
                          !empty($item['deskripsiProduk']) ||
                          !empty($item['terjual']) ||
                          !empty($item['sisaStok']) ||
                          !empty($item['createAt']) ||
                          !empty($item['updateAt']))
                          <tr>
                            <td>{{ $item['idProduk'] ?? 'ID Produk tidak ditemukan'}}</td>
                            <td>{{ $item['namaProduk'] ?? 'Nama produk tidak ditemukan'}}</td>
                            <td>
                              @if(!empty($item['urlFotoProduk']))
                                @foreach ($item['urlFotoProduk'] as $url)
                                    <a href="{{ $url }}" target="_blank">Lihat Foto</a><br>
                                @endforeach
                              @else
                                  Foto produk tidak ditemukan
                              @endif
                            </td>
                            <td>{{ $item['deskripsiProduk'] ?? 'Deskripsi tidak ditemukan'}}</td>
                            <td>
                              <a href="" data-toggle="modal" data-target="#modalLihat{{ $key }}" title="Detail">Lihat Varian</a>    
                            </td>
                            <td>{{ $item['terjual'] ?? '0'}}</td>
                            <td>{{ $item['sisaStok'] ?? '0'}}</td>
                            <td>{{ $item['createAt'] ?? 'Jejak waktu tidak ditemukan'}}</td>
                            <td>{{ $item['updateAt'] ?? 'Jejak waktu tidak ditemukan'}}</td>
                            <td>
                              <a href="" title="Edit produk" data-toggle="modal" data-target="#modalEdit{{ $key }}" title="Edit produk">
                                <i class="fas fa-edit" style="margin-right: 15px;"></i>
                              </a>    
                              <a href="" data-toggle="modal" data-target="" title="Hapus produk"><i class="fa-solid fa-trash" style="color: red"></i></a>     
                            </td>
                          </tr>

                          <!-- Modal Edit Produk -->
                          <div class="modal fade" id="modalEdit{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 700px; width: 100%;">
                              <div class="modal-content rounded-0">
                                <div class="modal-body p-4 px-5">
                                  <div class="main-content text-center">
                                    <a href="#" class="close-btn" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true"><span class="icon-close2"></span></span>
                                    </a>
                                    <div class="card-body">
                                      <h4 class="card-title">Edit Produk</h4>
                                      <form action="{{ route('update.produk', ['id' => $key]) }}" method="POST" enctype="multipart/form-data" id="formEdit{{ $key }}" class="forms-sample">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group row">
                                          <label for="edit_nama_produk" class="col-sm-3 col-form-label">ID Produk</label>
                                          <div class="col-sm-9">
                                            <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" class="form-control" name="key" value="{{ $key }}" readonly>
                                          </div>
                                        </div>

                                        <!-- Nama Produk -->
                                        <div class="form-group row">
                                          <label for="edit_nama_produk" class="col-sm-3 col-form-label">Nama Produk</label>
                                          <div class="col-sm-9">
                                            <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" class="form-control" id="edit_nama_produk" name="nama_produk" value="{{ $item['namaProduk'] ?? '' }}" required>
                                          </div>
                                        </div>

                                        <!-- Foto Produk -->
                                        <div class="form-group row">
                                          <label for="edit_foto_produk" class="col-sm-3 col-form-label">Foto Produk</label>
                                          <div class="col-sm-9">
                                            <img src="{{ $item['urlFotoProduk'] ?? '#' }}" alt="Gambar Varian" class="img-fluid rounded" style="max-height: 150px; margin-bottom: 8px;">
                                            <input type="file" style="border: 2px solid #8D0B41; border-radius: 4px;" multiple class="form-control" id="edit_foto_produk" name="foto_produk[]">
                                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah foto.</small>
                                          </div>
                                        </div>

                                        <!-- Deskripsi -->
                                        <div class="form-group row">
                                          <label for="edit_deskripsi_produk" class="col-sm-3 col-form-label">Deskripsi Produk</label>
                                          <div class="col-sm-9">
                                            <textarea class="form-control" style="border: 2px solid #8D0B41; border-radius: 4px;" style="border: 2px solid #8D0B41; border-radius: 4px;" id="edit_deskripsi_produk" name="deskripsi_produk" required>{{ $item['deskripsiProduk'] ?? '' }}</textarea>
                                          </div>
                                        </div>

                                        <!-- Varian Produk -->
                                        <div class="form-group row">
                                          <label class="col-sm-3 col-form-label">Varian Produk</label>
                                          <div class="col-sm-9">
                                            <div  class="edit-varian-container" id="editVarianContainer">
                                              @if (!empty($item['varian']))
                                                @foreach ($item['varian'] as $i => $varian)
                                                <div class="form-group p-2 mb-2" style="border: 2px solid #8D0B41; border-radius: 6px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                                  <img src="{{ $varian['gambar'] ?? '#' }}" alt="Gambar Varian" class="img-fluid rounded" style="max-height: 150px; margin-bottom: 8px;">
                                                  <label class="d-block text-left mt-15" style="margin-top: 8dp">Nama varian</label>
                                                  <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[nama][]" placeholder="Nama" value="{{ $varian['nama'] ?? '' }}" class="form-control mb-1" required>           
                                                  <label class="d-block text-left">Gambar Produk</label>
                                                  <input type="file" style="border: 1px solid #8D0B41; border-radius: 4px;" name="varian[gambar][]" placeholder="Gambar produk" value="{{ $varian['gambar'] ?? '' }}" class="form-control mb-1">    
                                                  <label class="d-block text-left">Ukuran</label>
                                                  <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[size][]" placeholder="Ukuran" value="{{ $varian['size'] ?? '' }}" class="form-control mb-1" required>
                                                  <label class="d-block text-left">Harga</label>
                                                  <input type="number" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[harga][]" placeholder="Harga" value="{{ $varian['harga'] ?? 0 }}" class="form-control mb-1" required>
                                                  <label class="d-block text-left">Stok tersedia</label>
                                                  <input type="number" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[stok][]" placeholder="Stok" value="{{ $varian['stok'] ?? 0 }}" class="form-control mb-1" required>
                                                  <label class="d-block text-left">Berat (Kg)</label>
                                                  <input type="number" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[berat][]" placeholder="Berat (Kg)" value="{{ $varian['berat'] ?? 0 }}" class="form-control" required>
                                                  <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-danger btn-sm" style="margin-top: 8px" onclick="hapusVarian(this)">× Hapus</button>
                                                  </div>
                                                </div>
                                                @endforeach
                                              @endif
                                            </div>
                                          </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary mr-2">Simpan Perubahan</button>
                                        <button class="btn btn-light" data-dismiss="modal" onclick="clearForm('formEdit')">Batal</button>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- End Modal Edit Produk -->

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
                          <!-- End modal hapus -->

                          <!-- Modal lihat varian -->
                          <div class="modal fade" id="modalLihat{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 700px; width: 100%;">
                              <div class="modal-content rounded-0">
                                <div class="modal-body p-4 px-5">                      
                                  <div class="main-content text-center">               
                                      <a href="#" class="close-btn" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"><span class="icon-close2"></span></span>
                                      </a>
                                      <div class="card-body">
                                        <h5 class="text-center mb-4 font-weight-bold">Detail Varian Produk</h5>
                                        @if(isset($item['varian']) && is_array($item['varian']))
                                          @foreach($item['varian'] as $index => $varian)
                                            <div class="p-3 mb-3" style="border: 2px solid #8D0B41; border-radius: 6px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                              <div class="row">
                                                <div class="col-md-4 text-center">
                                                  <img src="{{ $varian['gambar'] ?? '#' }}" alt="Gambar Varian" class="img-fluid rounded" style="max-height: 150px;">
                                                </div>
                                                <div class="col-md-8" style="align-items: start">
                                                  <h6 class="font-weight-bold">{{ $varian['nama'] ?? 'Nama tidak tersedia' }}</h6>
                                                  <p class="mb-1"><strong>Ukuran:</strong> {{ $varian['size'] ?? '-' }}</p>
                                                  <p class="mb-1"><strong>Harga:</strong> Rp{{ number_format($varian['harga'] ?? 0, 0, ',', '.') }}</p>
                                                  <p class="mb-0"><strong>Stok:</strong> {{ $varian['stok'] ?? 0 }}</p>
                                                  <p class="mb-0"><strong>Berat:</strong> {{ $varian['berat'] ?? 0 }} Kg</p>
                                                </div>
                                              </div>
                                            </div>
                                          @endforeach
                                        @else
                                          <p class="text-center text-muted">Tidak ada varian yang tersedia.</p>
                                        @endif
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- End modal lihat -->
                        @endif
                        @endforeach

                      @else
                        <tr>
                          <td colspan="10" style="text-align: center">Data Produk tidak ditemukan</td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                </div>

                <!-- Modal tambah produk -->
                <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 700px; width: 100%;">
                    <div class="modal-content rounded-0">
                      <div class="modal-body p-4 px-5">
                        <div class="main-content text-center">
                            <a href="#" class="close-btn" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true"><span class="icon-close2"></span></span>
                            </a>
                            <div class="card-body">
                              <h4 class="card-title">Tambah Produk</h4>
                              <form action="{{ route('tambah.produk') }}" method="POST" enctype="multipart/form-data" id="formTambah" class="forms-sample">
                              @csrf
                                <!-- Input nama produk -->
                                <div class="form-group row">
                                  <label for="nama_produk" class="col-sm-3 col-form-label">Nama Produk</label>
                                  <div class="col-sm-9">
                                    <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" class="form-control" id="nama_produk" name="nama_produk" placeholder="Masukkan nama produk" required>
                                  </div>
                                </div>

                                <!-- Input foto -->
                                <div class="form-group row">
                                  <label for="foto_produk" class="col-sm-3 col-form-label">Foto Produk</label>
                                  <div class="col-sm-9">
                                    <input type="file" style="border: 2px solid #8D0B41; border-radius: 4px;" multiple class="form-control" id="foto_produk[]" name="foto_produk[]" placeholder="Masukkan foto produk tanpa koma atau titik" required>
                                  </div>
                                </div>

                                <!-- Input deskripsi -->
                                <div class="form-group row">
                                  <label for="deskripsi_produk" class="col-sm-3 col-form-label">Deksripsi Produk</label>
                                  <div class="col-sm-9">
                                    <textarea class="form-control" style="border: 2px solid #8D0B41; border-radius: 4px;" id="deskripsi_produk" name="deskripsi_produk" placeholder="Masukkan harga produk" required></textarea>
                                  </div>
                                </div>

                                <!-- Input varian produk -->
                                <div class="form-group row">
                                  <label class="col-sm-3 col-form-label">Varian Produk</label>
                                  <div class="col-sm-9">
                                    <div id="varianContainer">
                                      <div class="varian-item p-3 mb-2 bg-light" style="border: 2px solid #8D0B41; border-radius: 6px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                        <div class="mb-2">
                                          <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[nama][]" class="form-control" placeholder="Nama Varian" required>
                                        </div>
                                        <div class="mb-2">
                                          <input type="file" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[gambar][]" class="form-control" accept="image/*" required>
                                        </div>
                                        <div class="mb-2">
                                          <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[size][]" class="form-control" placeholder="Ukuran produk" required>
                                        </div>
                                        <div class="mb-2">
                                          <input type="number" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[harga][]" class="form-control" placeholder="Harga Varian" min="0" required>
                                        </div>
                                        <div class="mb-2">
                                          <input type="number" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[stok][]" class="form-control" placeholder="Stok Varian" required>
                                        </div>
                                        <div class="mb-2">
                                          <input type="number" style="border: 2px solid #8D0B41; border-radius: 4px;" step="0.01" min="0" name="varian[berat][]" class="form-control" placeholder="Berat Varian (Kg)" required>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                          <button type="button" class="btn btn-danger btn-sm" onclick="hapusVarian(this)">× Hapus</button>
                                        </div>
                                      </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-success mt-2" onclick="tambahVarian()">+ Tambah Varian</button>
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
                <!-- End modal tambah produk -->
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
    function tambahVarian() {
      const container = document.getElementById('varianContainer');
      const varianItem = document.createElement('div');
      varianItem.classList.add('varian-item', 'p-3', 'mb-2', 'bg-light');
      varianItem.style.border = '2px solid #8D0B41';
      varianItem.style.borderRadius = '6px';
      varianItem.style.boxShadow = '0 2px 6px rgba(0, 0, 0, 0.1)';
  
      varianItem.innerHTML = `
        <div class="mb-2">
          <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[nama][]" class="form-control" placeholder="Nama Varian" required>
        </div>
        <div class="mb-2">
          <input type="file" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[gambar][]" class="form-control" accept="image/*" required>
        </div>
        <div class="mb-2">
          <input type="text" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[size][]" class="form-control" placeholder="Ukuran produk" required>
        </div>
        <div class="mb-2">
          <input type="number" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[harga][]" class="form-control" placeholder="Harga Varian" min="0" required>
        </div>
        <div class="mb-2">
          <input type="number" style="border: 2px solid #8D0B41; border-radius: 4px;" name="varian[stok][]" class="form-control" placeholder="Stok Varian" required>
        </div>
        <div class="mb-2">
          <input type="number" style="border: 2px solid #8D0B41; border-radius: 4px;" step="0.01" min="0" name="varian[berat][]" class="form-control" placeholder="Berat Varian (Kg)" required>
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-danger btn-sm" onclick="hapusVarian(this)">× Hapus</button>
        </div>
      `;
  
      container.appendChild(varianItem);
    }
  
    function hapusVarian(button) {
      button.closest('.varian-item').remove();
    }
  </script>

  <script>
    function hapusVarian(button) {
      const container = button.closest('.edit-varian-container');
      const items = container.querySelectorAll('.form-group');
      
      if (items.length > 1) {
        button.closest('.form-group').remove();
        toggleDeleteButtons(container); // update visibilitas tombol hapus
      }
    }
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

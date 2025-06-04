<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  
          <a class="navbar-brand brand-logo" href=""><img src="{{ asset('images/cms-logo.svg') }}" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href=""><img src="{{ asset('pengunjung/images/sascode-logo.jpg') }}" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-sort-variant"></span>
          </button>
        </div>  
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <!--<ul class="navbar-nav mr-lg-4 w-100">
          <li class="nav-item nav-search d-none d-lg-block w-100">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text" id="search">
                  <i class="mdi mdi-magnify"></i>
                </span>
              </div>
              <input type="text" id="global-search" class="form-control" placeholder="Cari Motif, Toko, dan Artikel terkait..." aria-label="search" aria-describedby="search">
            </div>
          </li>
        </ul>-->
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown mr-1">
            <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" href="https://wa.me/6289696210706?text=Saya%20Manajer%20Konten%2C%20terdapat%20perihal%20yang%20ingin%20saya%20tanyakan%20mengenai%20sistem">
              <i class="mdi mdi-comment-question-outline" title="Hubungi admin sistem"></i>
            </a>
          </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" href="https://wa.me/6289696210706?text=Saya%20Manajer%20Konten%2C%20terdapat%20perihal%20yang%20ingin%20saya%20tanyakan%20mengenai%20sistem">
              <i class="mdi mdi-account-settings" title="Pengaturan akun"></i>
            </a>
            <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
              @if (session('session') && is_array(session('session')))
                  @php
                    $admin = session('session');    
                  @endphp
                  <span class="nav-profile-name">{{ $admin['email'] }}</span>
              @endif
            </a>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
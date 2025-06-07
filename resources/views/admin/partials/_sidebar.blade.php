<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/home') }}">
              <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
              <span class="menu-title">Overview</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#post" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-newspaper menu-icon"></i>
              <span class="menu-title">Post</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="post">
              <ul class="nav flex-column sub-menu">                
                <li class="nav-item"> <a class="nav-link" href="{{ route('katalog') }}">S-Katalog</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('objek.3d') }}">Unggah Model 3D Produk (AR)</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('berita') }}">Rilis Media</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#partner-management" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-briefcase-outline menu-icon"></i>
              <span class="menu-title">Mitra</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="partner-management">
              <ul class="nav flex-column sub-menu">           
                <li class="nav-item"> <a class="nav-link" href="{{ url('/ka-pasar') }}">Mitra</a></li> <!-- Data mitra (ID) -->
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#user-management" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-account-multiple menu-icon"></i>
              <span class="menu-title">Pengguna Aplikasi</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="user-management">
              <ul class="nav flex-column sub-menu">                
                <li class="nav-item"> <a class="nav-link" href="{{ url('/users') }}">Pengguna Aplikasi</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ url('roles') }}">Level Pengguna</a></li>
              </ul>
            </div>
          </li>
          <!--<li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#app-management" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-android menu-icon"></i>
              <span class="menu-title">Analisis Data Aplikasi</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="app-management">
              <ul class="nav flex-column sub-menu">
              </ul>
            </div>
          </li>-->
          <li class="nav-item">
            <form id="logout-form" action="{{ route('keluar') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="#" class="nav-link btn btn-link text-decoration-none" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="mdi mdi-logout menu-icon"></i>
                <span class="menu-title">Keluar</span>
            </a>
          </li>
        </ul>
      </nav>
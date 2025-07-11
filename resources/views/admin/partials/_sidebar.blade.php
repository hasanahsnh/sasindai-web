<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/home') }}">
              <i class="mdi mdi-view-dashboard menu-icon"></i>
              <span class="menu-title">Overview</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#post" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-newspaper menu-icon"></i>
              <span class="menu-title">Unggahan</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="post">
              <ul class="nav flex-column sub-menu">                            
                <li class="nav-item"> <a class="nav-link" href="{{ route('katalog') }}">Katalog Motif</a></li>    
                <li class="nav-item"> <a class="nav-link" href="{{ route('objek.3d') }}">Model 3D Produk (AR)</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('berita') }}">Rilis Media</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#layanan" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-tooltip menu-icon"></i>
              <span class="menu-title">Layanan</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="layanan">
              <ul class="nav flex-column sub-menu">                            
                <li class="nav-item"> <a class="nav-link" href="{{ route('layanan.fitur.aplikasi') }}">Kelola Layanan</a></li>    
                <li class="nav-item"> <a class="nav-link" href="{{ route('push.notifikasi') }}">Push Broadcast Notifikasi</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#user-management" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-account-multiple menu-icon"></i>
              <span class="menu-title">Users</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="user-management">
              <ul class="nav flex-column sub-menu">                
                <li class="nav-item"> <a class="nav-link" href="{{ url('/users') }}">User Aplikasi Sasindai</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ url('roles') }}">User Level</a></li>
              </ul>
            </div>
          </li>
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
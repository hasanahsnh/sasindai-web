<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/dashboard-mitra') }}">
              <i class="mdi mdi-view-dashboard menu-icon"></i>
              <span class="menu-title">Overview</span>
            </a>
          </li>
          <!-- Mitra Management -->
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/produk') }}" onclick="return checkTokoStatus('{{ $statusVerifikasi }}')">
              <i class="mdi mdi-package menu-icon"></i>
              <span class="menu-title">Daftar Produk</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">
              <i class="mdi mdi-chart-bar menu-icon"></i>
              <span class="menu-title">Statistik</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">
              <i class="mdi mdi-cart menu-icon"></i>
              <span class="menu-title">Pesanan</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">
              <i class="mdi mdi-motorbike menu-icon"></i>
              <span class="menu-title">Pengiriman Barang</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">
              <i class="mdi mdi-cash menu-icon"></i>
              <span class="menu-title">Riwayat Penerimaan Dana</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/data-toko') }}">
              <i class="mdi mdi-store menu-icon"></i>
              <span class="menu-title">Informasi Toko</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">
              <i class="mdi mdi-library-books menu-icon"></i>
              <span class="menu-title">Tutorial</span>
            </a>
          </li>
          <!-- End Mitra Management -->
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/faq') }}">
              <i class="mdi mdi-comment-question-outline menu-icon"></i>
              <span class="menu-title">FAQ</span>
            </a>
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
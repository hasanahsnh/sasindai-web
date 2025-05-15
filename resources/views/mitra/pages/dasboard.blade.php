<p>Role: {{ $mitraRole }}</p>
<p>Email: {{ $mitraData['email'] ?? 'Email tidak tersedia' }}</p>
<p>Nama Lengkap: {{ $mitraData['namaLengkap'] ?? 'Email tidak tersedia' }}</p>

<form id="logout-form" action="{{ route('keluar') }}" method="POST" style="display: none;">
    @csrf
</form>
<a href="#" class="nav-link btn btn-link text-decoration-none" 
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="mdi mdi-logout menu-icon"></i>
    <span class="menu-title">Keluar</span>
</a>
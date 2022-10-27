<aside id="sidebar-wrapper">
  <div class="sidebar-brand">
    <a href="{{ route('admin-dashboard') }}">{{ env('APP_NAME') }}</a>
  </div>
  <div class="sidebar-brand sidebar-brand-sm">
    <a href="index.html">Lokasi Desa</a>
  </div>
  <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <li class="{{ request()->segment(2) == 'dashboard' ? ' active' : '' }}"><a class="nav-link" href="{{ route('user-dashboard') }}"><i class="fa fa-columns"></i> <span>Dashboard</span></a></li>
      <li class="menu-header">Profil & Properti</li>
      <li class="{{ request()->segment(2) == 'data-diri' ? ' active' : '' }}"><a class="nav-link" href="{{route('data-index')}}"><i class="fa fa-user"></i> <span>Data Diri</span></a></li>
      <li class="{{ request()->segment(2) == 'properti' ? ' active' : '' }}"><a class="nav-link" href="{{ route('properti-index') }}"><i class="fa fa-home"></i> <span>Daftar Properti</span></a></li>
      <li class="menu-header">Retribusi & Pengangkutan</li>
      <li class="{{ request()->segment(2) == 'retribusi' ? ' active' : '' }}"><a class="nav-link" href="{{ route('retribusi-index') }}"><i class="fa fa-receipt"></i> <span>Retribusi Sampah</span></a></li>
      <li class="{{ request()->segment(2) == 'request' ? ' active' : '' }}"><a class="nav-link" href="{{ route('request-index') }}"><i class="fa fa-truck"></i> <span>Request Pengangkutan</span></a></li>
      <li class="menu-header">Pembayaran</li>
      <li class=""><a class="nav-link" href="{{route('pembayaran-index')}}"><i class="fa fa-credit-card"></i> <span>Pembayaran</span></a></li>

    </ul>
</aside>

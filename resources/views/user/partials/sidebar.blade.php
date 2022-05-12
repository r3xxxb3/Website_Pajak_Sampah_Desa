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
      <li class="{{ request()->segment(2) == 'properti' ? ' active' : '' }}"><a class="nav-link" href="{{ route('properti-index') }}"><i class="fa fa-home"></i> <span>Daftar Properti</span></a></li>
      <li class=""><a class="nav-link" href=""><i class="fa fa-credit-card"></i> <span>Retribusi Sampah</span></a></li>
      <li class=""><a class="nav-link" href=""><i class="fa fa-truck"></i> <span>Request Pengangkutan</span></a></li>
      
    </ul>
</aside>

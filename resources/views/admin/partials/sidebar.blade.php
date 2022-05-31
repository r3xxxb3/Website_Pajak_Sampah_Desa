<aside id="sidebar-wrapper">
  <div class="sidebar-brand">
    <a href="{{ route('admin-dashboard') }}">{{ env('APP_NAME') }}</a>
  </div>
  <div class="sidebar-brand sidebar-brand-sm">
    <a href="index.html">Lokasi Desa</a>
  </div>
  <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <li class="{{ Request::route()->getName() == 'admin-dashboard' ? ' active' : '' }}"><a class="nav-link" href="{{ route('admin-dashboard') }}"><i class="fa fa-columns"></i> <span>Dashboard</span></a></li>
      <li class="menu-header">Master Data</li>
      <li class=" {{ request()->segment(2) == 'masterdata' ? 'active' : '' }}">
          <a class="nav-link {{ Request::is('admin/masterdata*') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseMaster" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fa fa-cogs"></i>
            <span class="text-dark">Master Data</span>
          </a>
      </li>
      <ul id="collapseMaster" class="collapse {{ Request::is('admin/masterdata*') ? 'show' : '' }}" aria-labelledby="headingUtilities">
        <div class="bg-white py-2 collapse-inner rounded ">
          <!-- <li  class ="text-dark collapse-show {{ Request::route()->getName() == 'masterdata-kota-index' ? 'active' : '' }}"><a class="nav-link" href="{{route('masterdata-kota-index')}}">Kota</a></li>
          <li  class ="text-dark collapse-show {{ Request::route()->getName() == 'masterdata-kecamatan-index' ? 'active' : '' }}"><a class="nav-link" href="{{route('masterdata-kecamatan-index')}}">Kecamatan</a></li>
          <li  class ="text-dark collapse-show {{ Request::route()->getName() == 'masterdata-desa-index' ? 'active' : '' }}"><a class="nav-link" href="{{route('masterdata-desa-index')}}">Desa</a></li> -->
          <li  class ="text-dark collapse-show {{ request()->segment(3) == 'jadwal' ? 'active' : '' }}"><a class="nav-link" href="{{route('masterdata-jadwal-index')}}">Jadwal Pengangkutan</a></li>
          <!-- <li  class ="text-dark collapse-show {{ request()->segment(3) == 'retribusi' ? 'active' : '' }}"><a class="nav-link" href="{{route('masterdata-retribusi-index')}}">Standar Retribusi</a></li> -->
          <li  class ="text-dark collapse-show {{ request()->segment(3) == 'jenis-jasa' ? 'active' : '' }}"><a class="nav-link" href="{{route('masterdata-jenisjasa-index')}}">Jenis Jasa</a></li>
          <li  class ="text-dark collapse-show {{ request()->segment(3) == 'jenis-sampah' ? 'active' : '' }}"><a class="nav-link" href="{{route('masterdata-jenis-index')}}">Jenis Sampah</a></li>
        </div>
      </ul>
      <li class="menu-header">Manajemen Pengguna</li>
      <li class="{{ request()->segment(2) == 'pegawai' ? 'active' : '' }}"><a class="nav-link" href="{{ route('pegawai-index') }}"><i class="fa fa-briefcase"></i> <span>Manajemen Pegawai</span></a></li>
      <li class="{{ request()->segment(2) == 'pengguna' ? 'active' : '' }}"><a class="nav-link" href="{{ route('pengguna-index') }}"><i class="fa fa-id-card"></i> <span>Manajemen Pelanggan</span></a></li>
      <li class="menu-header">Retribusi & Pengangkutan</li>
      <li class="{{ request()->segment(2) == 'retribusi' ? 'active' : '' }}"><a class="nav-link" href="{{ route('admin-retribusi-index') }}"><i class="fa fa-credit-card"></i> <span>Manajemen Retribusi</span></a></li>
      <li class=""><a class="nav-link" href=""><i class="fa fa-truck"></i> <span>Request Pengangkutan</span></a></li>
      <li class="menu-header">Pembayaran</li>
      <li class=""><a class="nav-link" href=""><i class="fa fa-money-bill-wave-alt"></i> <span>Manajemen Pembayaran</span></a></li>
      <li class="menu-header">Reporting</li>
      <li class=""><a class="nav-link" href=""><i class="fa fa-file-chart-pie"></i> <span>Report</span></a></li>
    </ul>
</aside>

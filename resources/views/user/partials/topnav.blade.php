<form class="form-inline mr-auto" >
  <ul class="navbar-nav mr-3">
    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
  </ul>
  <!-- <div class="search-element">
    <input class="form-control" value="{{ Request::get('query') }}" name="query" type="search" placeholder="Search" aria-label="Search" data-width="250">
    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
    <div class="search-backdrop"></div>
    {{-- @include('user.partials.searchhistory') --}}
  </div> -->
</form>
<ul class="navbar-nav navbar-right">
  <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg{{' beep'}}"><i class="far fa-bell"></i></a>
    <div class="dropdown-menu dropdown-list dropdown-menu-right">
      <div class="dropdown-header">Notifications
        <div class="float-right">
          <a href="#">Mark All As Read</a>
        </div>
      </div>
      <div class="dropdown-list-content dropdown-list-icons">
      @if(Auth::guard('web')->user()->unreadNotifications->count())
        @foreach(Auth::guard('web')->user()->unreadNotifications as $notif)
          @if($notif->type == "App\Notifications\PropertiNotif")
            <a href="{{Route('user-properti-redirect', $notif->id)}}"  class="dropdown-item dropdown-item-unread">
          @elseif($notif->type == "App\Notifications\RetribusiNotif")
            <a href="{{Route('user-retribusi-redirect', $notif->id)}}"  class="dropdown-item dropdown-item-unread">
          @elseif($notif->type == "App\Notifications\PengangkutanNotif")
            <a href="{{Route('user-request-redirect', $notif->id)}}"  class="dropdown-item dropdown-item-unread">
          @elseif($notif->type == "App\Notifications\PembayaranNotif")
            <a href="{{Route('user-pembayaran-redirect', $notif->id)}}"  class="dropdown-item dropdown-item-unread">
          @endif
            @if($notif->data['type']== "update")
            <div class="dropdown-item-icon bg-warning text-white">
              <i class="fas fa-pen"></i>
            </div>
            @elseif($notif->data['type'] == "create")
            <div class="dropdown-item-icon bg-success text-white">
              <i class="fas fa-plus"></i>
            </div>
            @elseif($notif->data['type'] == "cancel")
            <div class="dropdown-item-icon bg-danger text-white">
              <i class="fas fa-exclamation"></i>
            </div>
            @elseif($notif->data['type'] == "confirm")
            <div class="dropdown-item-icon bg-success text-white">
              <i class="fas fa-check"></i>
            </div>
            @elseif($notif->data['type'] == "verify")
            <div class="dropdown-item-icon bg-success text-white">
              <i class="fas fa-check-double"></i>
            </div>
            @endif
          <div class="dropdown-item-desc">
            {{$notif->data['message']}}
            <div class="time text-primary">{{now()->diffInMinutes($notif->created_at)}} Min Ago</div>
          </div>
        </a>
        @endforeach
      @else
        <p class="text-muted p-2 text-center">No notifications found!</p>
      @endif
    </div>
  </li>
  <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
  <!-- <img alt="image" src="{{ Auth::guard('web')->user()->avatarlink }}" class="rounded-circle mr-1"> -->
  {{auth()->guard('web')->user()->kependudukan->nama}}
    <!-- <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::guard('web')->user()}}</div></a> -->
    <div class="dropdown-menu dropdown-menu-right">
      <!-- <div class="dropdown-title">Welcome, {{ Auth::guard('web')->user() }}</div> -->
      <a href="{{route('home')}}" class="dropdown-item has-icon">
        <i class="fa fa-arrow-right"></i> Back To Landing Page
      </a>
      <a href="" class="dropdown-item has-icon">
        <i class="far fa-user"></i> Profile Settings
      </a>
      <div class="dropdown-divider"></div>
      <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </li>
</ul>

<form class="form-inline mr-auto" >
  <ul class="navbar-nav mr-3">
    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
  </ul>
  <!-- <div class="search-element">
    <input class="form-control" value="{{ Request::get('query') }}" name="query" type="search" placeholder="Search" aria-label="Search" data-width="250">
    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
    <div class="search-backdrop"></div>
    {{-- @include('admin.partials.searchhistory') --}}
  </div> -->
</form>
<ul class="navbar-nav navbar-right">
  <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg{{' beep'}}"><i class="far fa-bell"></i></a>
    <div class="dropdown-menu dropdown-list dropdown-menu-right">
      <div class="dropdown-header">Notifications
        <div class="float-right">
          <a href="#" >Mark All As Read</a>
        </div>
      </div>
      <div class="dropdown-list-content dropdown-list-icons">
      @if(Auth::guard('admin')->user()->unreadNotifications->count())
        @foreach(Auth::guard('admin')->user()->unreadNotifications as $notif)
        <a href="{{Route('pengguna-edit', $notif->data['user_id'])}}"  class="dropdown-item dropdown-item-unread">
          <div class="dropdown-item-icon bg-primary text-white">
            @if($notif->data['type']== "update")
              <i class="fas fa-pen"></i>
            @elseif($notif->data['type'] == "create")
              <i class="fas fa-plus"></i>
            @elseif($notif->data['type'] == "cancel")
              <i class="fas fa-exclamation"></i>
            @endif
          </div>
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
  <img alt="image" src="{{ Auth::guard('admin')->user()->avatarlink }}" class="rounded-circle mr-1">
    <!-- <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::guard('admin')->user()}}</div></a> -->
    <div class="dropdown-menu dropdown-menu-right">
      <!-- <div class="dropdown-title">Welcome, {{ Auth::guard('admin')->user() }}</div> -->
      <a href="" class="dropdown-item has-icon">
        <i class="far fa-user"></i> Profile Settings
      </a>
      <div class="dropdown-divider"></div>
      <a href="{{ route('admin-logout') }}" class="dropdown-item has-icon text-danger">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </div>
  </li>
</ul>

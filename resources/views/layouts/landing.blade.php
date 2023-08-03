<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Landing Page Retribusi</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
      
    </head>
    <body>
  <section class="h-100 w-100" style="box-sizing: border-box">
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

      .navbar-1-5.navbar-light .navbar-nav .nav-link {
        color: #092a33;
      }

      .navbar-1-5.navbar-light .navbar-nav .nav-link.active {
        font-weight: 500;
      }

      .navbar-1-5 .btn-get-started {
        border-radius: 20px;
        padding: 12px 30px;
        font-weight: 500;
      }

      .navbar-1-5 .btn-get-started-green {
        background-color: #32735f;
        transition: 0.3s;
      }

      .navbar-1-5 .btn-get-started-green:hover {
        background-color: #2a7e65;
        transition: 0.3s;
      }
    </style>
  </section>

  <section class="h-100 w-100 bg-white" style="box-sizing: border-box">
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

      .header-4-2 .modal-item.modal {
        top: 2rem;
      }

      .header-4-2 .navbar,
      .header-4-2 .hero {
        padding: 3rem 2rem;
      }

      .header-4-2 .navbar-light .navbar-nav .nav-link {
        font: 300 18px/1.5rem Poppins, sans-serif;
        color: #1d1e3c;
        transition: 0.3s;
      }

      .header-4-2 .navbar-light .navbar-nav .nav-link:hover {
        font: 600 18px/1.5rem Poppins, sans-serif;
        color: #1d1e3c;
        transition: 0.3s;
      }

      .header-4-2 .navbar-light .navbar-nav .active>.nav-link,
      .header-4-2 .navbar-light .navbar-nav .nav-link.active,
      .header-4-2 .navbar-light .navbar-nav .nav-link.show,
      .header-4-2 .navbar-light .navbar-nav .show>.nav-link {
        font-weight: 600;
        transition: 0.3s;
      }

      .header-4-2 .navbar-light .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(0, 0, 0, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
      }

      .header-4-2 .btn:focus,
      .header-4-2 .btn:active {
        outline: none !important;
      }

      .header-4-2 .btn-fill {
        font: 600 18px / normal Poppins, sans-serif;
        background-color: #27c499;
        border-radius: 12px;
        padding: 12px 32px;
        transition: 0.3s;
      }

      .header-4-2 .btn-fill:hover {
        --tw-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
          0 4px 6px -2px rgba(0, 0, 0, 0.05);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000),
          var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        transition: 0.3s;
      }

      .header-4-2 .btn-no-fill {
        font: 300 18px/1.75rem Poppins, sans-serif;
        color: #1d1e3c;
        padding: 12px 32px;
        transition: 0.3s;
      }

      .header-4-2 .modal-item .modal-dialog .modal-content {
        border-radius: 8px;
        transition: 0.3s;
      }

      .header-4-2 .responsive li a {
        padding: 1rem;
        transition: 0.3s;
      }

      .header-4-2 .text-caption {
        font: 600 0.875rem/1.625 Poppins, sans-serif;
        margin-bottom: 2rem;
        color: #27c499;
      }

      .header-4-2 .left-column {
        margin-bottom: 2.75rem;
        width: 100%;
      }

      .header-4-2 .right-column {
        width: 100%;
      }

      .header-4-2 .title-text-big {
        font: 600 2.25rem/2.5rem Poppins, sans-serif;
        margin-bottom: 2rem;
        color: #272e35;
      }

      .header-4-2 .btn-try {
        font: 600 1rem/1.5rem Poppins, sans-serif;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        background-color: #27c499;
        transition: 0.3s;
      }

      .header-4-2 .btn-try:hover {
        --tw-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
          0 4px 6px -2px rgba(0, 0, 0, 0.05);
        box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000),
          var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        transition: 0.3s;
      }

      .header-4-2 .btn-outline {
        font: 400 1rem/1.5rem Poppins, sans-serif;
        border: 1px solid #555b61;
        color: #555b61;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem;
        background-color: transparent;
        transition: 0.3s;
      }

      .header-4-2 .btn-outline:hover {
        border: 1px solid #27c499;
        color: #27c499;
        transition: 0.3s;
      }

      .header-4-2 .btn-outline:hover div path {
        fill: #27c499;
        transition: 0.3s;
      }

      @media (min-width: 576px) {
        .header-4-2 .modal-item .modal-dialog {
          max-width: 95%;
          border-radius: 12px;
        }

        .header-4-2 .navbar {
          padding: 3rem 2rem;
        }

        .header-4-2 .hero {
          padding: 3rem 2rem 5rem;
        }

        .header-4-2 .title-text-big {
          font-size: 3rem;
          line-height: 1.2;
        }
      }

      @media (min-width: 768px) {
        .header-4-2 .navbar {
          padding: 3rem 4rem;
        }

        .header-4-2 .hero {
          padding: 3rem 4rem 5rem;
        }

        .header-4-2 .left-column {
          margin-bottom: 3rem;
        }
      }

      @media (min-width: 992px) {
        .header-4-2 .navbar-expand-lg .navbar-nav .nav-link {
          padding-right: 1.25rem;
          padding-left: 1.25rem;
        }

        .header-4-2 .navbar {
          padding: 3rem 6rem;
        }

        .header-4-2 .hero {
          padding: 3rem 6rem 5rem;
        }

        .header-4-2 .left-column {
          width: 50%;
          margin-bottom: 0;
        }

        .header-4-2 .right-column {
          width: 50%;
        }

        .header-4-2 .title-text-big {
          font-size: 3.75rem;
          line-height: 1.2;
        }

        .header-4-2 .title-text-medium {
          font-size: 1.75rem;
          line-height: 1.2;
        }

        .header-4-2 .title-text-small {
          font-size: 1.00rem;
          line-height: 1.2;
        }
      }
    </style>
    <div class="header-4-2 container-xxl mx-auto p-0 position-relative" style="font-family: 'Poppins', sans-serif">
      <nav class="navbar navbar-expand-lg navbar-light">
        <!-- <a href="#">
          <img style="margin-right: 0.75rem"
            src="http://api.elements.buildwithangga.com/storage/files/2/assets/Header/Header2/Header-2-5.png" alt="" />
        </a> -->
        <h1 class="title-text-medium mb-1">
            <p>BUPDA </p>
            <p class="title-text-small" > Unit Pengelolaan Sampah</p>
        </h1>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="modal" data-bs-target="#targetModal-item">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="modal-item modal fade" id="targetModal-item" tabindex="-1" role="dialog"
          aria-labelledby="targetModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content bg-white border-0">
              <div class="modal-header border-0" style="padding: 2rem; padding-bottom: 0">
                <a class="modal-title" id="targetModalLabel">
                  <img style="margin-top: 0.5rem"
                    src="http://api.elements.buildwithangga.com/storage/files/2/assets/Header/Header2/Header-2-5.png"
                    alt="" />
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body" style="padding: 2rem; padding-top: 0; padding-bottom: 0">
                <ul class="navbar-nav responsive me-auto mt-2 mt-lg-0">
                  @if(auth()->guard('web')->check())
                  <li class="nav-item">
                    <a class="nav-link" href="{{Route('user-dashboard')}}">Dashboard</a>
                  </li>
                  @endif
                </ul>
              </div>
              <div class="modal-footer border-0 gap-3" style="padding: 2rem; padding-top: 0.75rem">
              @if(auth()->guard('web')->check())
                <a class="btn btn-fill btn-no-fill" href="{{Route('user-dashboard')}}">Dashboard</a>
                <a class="btn btn-default btn-no-fill" href="{{ route('logout') }}">Keluar</a>
              @else
                <a class="btn btn-default btn-no-fill" href="{{ route('login-page') }}">Masuk</a>
                <a class="btn btn-fill text-white" href="{{ route('register-page') }}">Daftar</a>
              @endif
              </div>
            </div>
          </div>
        </div>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo">
          <ul class="navbar-nav me-auto mt-2 mt-lg-0">
            <!-- @if(auth()->guard('web')->check())
            <li class="nav-item">
              <a class="nav-link" href="{{Route('user-dashboard')}}">Dashboard</a>
            </li>
            @endif -->
          </ul>
          <div class="gap-3">
              @if(auth()->guard('web')->check())
                <a class="btn btn-fill btn-no-fill" href="{{Route('user-dashboard')}}">Dashboard</a>
                <a class="btn btn-default btn-no-fill" href="{{ route('logout') }}">Keluar</a>
              @else
                <a class="btn btn-default btn-no-fill" href="{{ route('login-page') }}">Masuk</a>
                <a class="btn btn-fill text-white" href="{{ route('register-page') }}">Daftar</a>
              @endif
          </div>
        </div>
      </nav>

      <div>
        <div class="mx-auto d-flex flex-lg-row flex-column hero">
          <!-- Left Column -->
          <div
            class="left-column d-flex flex-lg-grow-1 flex-column align-items-lg-start text-lg-start align-items-center text-center">
            <p class="text-caption"></p>
            <h1 class="title-text-big mb-1">
              Selamat Datang !
            </h1>
            @if(auth()->guard('web')->check())
            <p>{{auth()->guard('web')->user()->kependudukan->nama}}</p>
            @endif
            <!-- <h4 class="text-small ">Daftar untuk lingkungan dan manajemen kebersihan yang lebih baik</h4> -->
            <div class="d-flex flex-sm-row flex-column align-items-center mx-lg-0 mx-auto justify-content-center gap-3 mt-5">
            @if(auth()->guard('web')->check())

            @else
              <a href="{{route('register-page')}}" class="btn d-inline-flex mb-md-0 btn-try text-white">
                Daftar Sekarang
              </a>
            @endif
              <!-- <button class="btn btn-outline">
                <div class="d-flex align-items-center">
                  <svg class="me-2" width="13" height="12" viewBox="0 0 13 13" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M10.9293 7.99988L6.66668 5.15788V10.8419L10.9293 7.99988ZM12.9173 8.27722L5.85134 12.9879C5.80115 13.0213 5.74283 13.0404 5.6826 13.0433C5.62238 13.0462 5.5625 13.0327 5.50934 13.0042C5.45619 12.9758 5.41175 12.9334 5.38075 12.8817C5.34976 12.83 5.33337 12.7708 5.33334 12.7105V3.28922C5.33337 3.22892 5.34976 3.16976 5.38075 3.11804C5.41175 3.06633 5.45619 3.02398 5.50934 2.99552C5.5625 2.96706 5.62238 2.95355 5.6826 2.95644C5.74283 2.95932 5.80115 2.97848 5.85134 3.01188L12.9173 7.72255C12.963 7.75299 13.0004 7.79423 13.0263 7.84261C13.0522 7.89099 13.0658 7.94501 13.0658 7.99988C13.0658 8.05475 13.0522 8.10878 13.0263 8.15716C13.0004 8.20553 12.963 8.24678 12.9173 8.27722Z"
                      fill="#555B61" />
                  </svg>
                  Watch the video
                </div>
              </button> -->
            </div>
          </div>
          <!-- Right Column -->
          <div class="right-column text-center d-flex justify-content-lg-end justify-content-center pe-0">
            <img id="img-fluid" class="h-auto mw-100 p-4 " style="border-radius : 30%"
              src="http://myrottenproject.org/assets/img/landing/1.jpg"
              alt="" />
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <section class="h1-00 w-100 bg-white" style="box-sizing: border-box">
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

      .content-3-2 .btn:focus,
      .content-3-2 .btn:active {
        outline: none !important;
      }

      .content-3-2 {
        padding: 5rem 2rem;
      }

      .content-3-2 .img-hero {
        width: 100%;
        margin-bottom: 3rem;
      }

      .content-3-2 .right-column {
        width: 100%;
      }

      .content-3-2 .title-text {
        font: 600 1.875rem/2.25rem Poppins, sans-serif;
        margin-bottom: 2.5rem;
        letter-spacing: -0.025em;
        color: #121212;
      }

      .content-3-2 .title-caption {
        font: 500 1.5rem/2rem Poppins, sans-serif;
        margin-bottom: 1.25rem;
        color: #121212;
      }

      .content-3-2 .circle {
        font: 500 1.25rem/1.75rem Poppins, sans-serif;
        height: 3rem;
        width: 3rem;
        margin-bottom: 1.25rem;
        border-radius: 9999px;
        background-color: #27c499;
      }

      .content-3-2 .text-caption {
        font: 400 1rem/1.75rem Poppins, sans-serif;
        letter-spacing: 0.025em;
        color: #565656;
      }

      .content-3-2 .btn-learn {
        font: 600 1rem/1.5rem Poppins, sans-serif;
        padding: 1rem 2.5rem;
        background-color: #27c499;
        transition: 0.3s;
        letter-spacing: 0.025em;
        border-radius: 0.75rem;
      }

      .content-3-2 .btn:hover {
        background-color: #45dbb2;
        transition: 0.3s;
      }

      @media (min-width: 768px) {
        .content-3-2 .title-text {
          font: 600 2.25rem/2.5rem Poppins, sans-serif;
        }
      }

      @media (min-width: 992px) {
        .content-3-2 .img-hero {
          width: 50%;
          margin-bottom: 0;
        }

        .content-3-2 .right-column {
          width: 50%;
        }

        .content-3-2 .circle {
          margin-right: 1.25rem;
          margin-bottom: 0;
        }
      }
    </style>
    <div class="content-3-2 container-xxl mx-auto  position-relative" style="font-family: 'Poppins', sans-serif">
      <div class="d-flex flex-lg-row flex-column align-items-center">
        <!-- Left Column -->
        <div class="img-hero text-center justify-content-center d-flex">
          <img id="hero" class="img-fluid p-4" style="border-radius : 30%"
            src="http://myrottenproject.org/assets/img/landing/2.jpg"
            alt="" />
        </div>

        <!-- Right Column -->
        <div class="right-column d-flex flex-column align-items-lg-start align-items-center text-lg-start text-center p-5">
          <h2 class="title-text">Jenis Sampah</h2>
          <ul style="padding: 0; margin: 0">
          <!-- Kustomisasi cms untuk user` -->
            @foreach($jenis as $j)
            <li class="list-unstyled" style="margin-bottom: 2rem">
                <h4
                  class="title-caption d-flex flex-lg-row flex-column align-items-center justify-content-lg-start justify-content-center">
                  <span class="circle text-white d-flex align-items-center justify-content-center">
                    {{$loop->index + 1}}
                  </span>
                  {{$j->jenis_sampah}}
                </h4>
                <p class="text-caption">
                  {{$j->deskripsi}}
                  <!-- We have provided highly experienced mentors<br class="d-sm-inline d-none" />
                  for several years. -->
                </p>
            </li>
                @if(($loop->index + 1) == 3)
                    @break
                @endif
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="h-100 w-100 bg-white" style="box-sizing: border-box">
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

      .content-3-2 .btn:focus,
      .content-3-2 .btn:active {
        outline: none !important;
      }

      .content-3-2 {
        padding: 5rem 2rem;
      }

      .content-3-2 .img-hero {
        width: 100%;
        margin-bottom: 3rem;
        margin-top:2rem;
      }

      .content-3-2 .right-column {
        width: 100%;
      }

      .content-3-2 .title-text {
        font: 600 1.875rem/2.25rem Poppins, sans-serif;
        margin-bottom: 2.5rem;
        letter-spacing: -0.025em;
        color: #121212;
      }

      .content-3-2 .title-caption {
        font: 500 1.5rem/2rem Poppins, sans-serif;
        margin-bottom: 1.25rem;
        color: #121212;
      }

      .content-3-2 .circle {
        font: 500 1.25rem/1.75rem Poppins, sans-serif;
        height: 3rem;
        width: 3rem;
        margin-bottom: 1.25rem;
        border-radius: 9999px;
        background-color: #27c499;
      }

      .content-3-2 .text-caption {
        font: 400 1rem/1.75rem Poppins, sans-serif;
        letter-spacing: 0.025em;
        color: #565656;
      }

      .content-3-2 .btn-learn {
        font: 600 1rem/1.5rem Poppins, sans-serif;
        padding: 1rem 2.5rem;
        background-color: #27c499;
        transition: 0.3s;
        letter-spacing: 0.025em;
        border-radius: 0.75rem;
      }

      .content-3-2 .btn:hover {
        background-color: #45dbb2;
        transition: 0.3s;
      }

      @media (min-width: 768px) {
        .content-3-2 .title-text {
          font: 600 2.25rem/2.5rem Poppins, sans-serif;
        }
      }

      @media (min-width: 992px) {
        .content-3-2 .img-hero {
          width: 50%;
          margin-bottom: 0;
        }

        .content-3-2 .right-column {
          width: 30%;
        }

        .content-3-2 .circle {
          margin-right: 1.25rem;
          margin-bottom: 0;
        }

        .content-3-2 .right-column-2{
          width: 50%;
          margin-right: 1.25rem;
        }

        div.dataTables_wrapper div.dataTables_filter label {
          width: 100%; 
        }

        div.dataTables_wrapper div.dataTables_filter input {
          width: 100%; 
        }

        div.dataTables_wrapper div.dataTables_length {
          width: 100%;
        }
        
        div.dataTables_wrapper div.dataTables_length select {
          width: 20%;
          margin: none; 
        }

        div.dataTables_wrapper div.dataTables_length label {
          width: 100%; 
        }

        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
            max-width:1200px;
            }
        }
      }
    </style>
    <div class="content-3-2  position-relative" style="font-family: 'Poppins', sans-serif">
      <div class="d-flex align-items-center">
        <!-- Left Column -->
        <div class="img-hero justify-content-center d-flex">
          <h2 class="title-text">Cari Jadwal Pengangkutan</h2>
        </div>

        <!-- Right Column -->
        <div class="right-column d-flex flex-column align-items-lg-start align-items-center text-lg-start text-center">
          <select class="form-control desa @error('desa') is-invalid @enderror" id="desa" name="desa" >
              <option value="">Pilih Desa Adat</option>
                @foreach($desa as $d)
                  <!--  -->
                <option value="{{$d->id}}">{{$d->desadat_nama}}</option>
                @endforeach
          </select>
        </div>
        <div class= "content-3-2 flex-column align-items-center">
          <button class="btn btn-learn text-white filter" data-toggle="modal" data-target="#modal-single">Cari</button>
        </div>
      </div>
    </div>
  </section> 

  <section>
  <div class="modal fade" id="modal-single">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title namaDesa" id="namaDesa" name="namaDesa"></h5>
          </div>
          <div class="modal-body">
            <div id="myDIV" style="display: block">
              <div class="row justify-content-between mb-3">
                <div class="col">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                          <tr class="table-primary">
                              <th>Hari</th>
                              <th>Mulai</th>
                              <th>Selesai</th>
                              <th>Jenis Sampah</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
  </section>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
      <script src="{{ route('js.dynamic') }}"></script>
      <script src="{{ asset('js/app.js') }}?{{ uniqid() }}"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
      <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
      <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
      <script src="{{ asset('assets/js/stisla.js') }}"></script>
      <script src="{{ asset('assets/js/scripts.js') }}"></script>
      <script>
        var table = $('#dataTable').DataTable({
            "oLanguage":{
                "sSearch": "Cari:",
                "sZeroRecords": "Data tidak ditemukan",
                "sSearchPlaceholder": "Cari jadwal",
                "infoEmpty": "Menampilkan 0 data",
                "infoFiltered": "(dari _MAX_ data)",
                "sLengthMenu": "Tampilkan _MENU_ data",
            },
            "language":{
                "paginate": {
                        "previous": 'Sebelumnya',
                        "next": 'Berikutnya'
                    },
                "info": "Menampilkan _START_ s/d _END_ dari _MAX_ data",
            },
            pageLength: 5,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "All"]]
        });
        $(document).ready( function(){
            $('.filter').on('click', function(e){
                e.preventDefault();
                var id = $('#desa').val();
                console.log(id);
                var desa = $('#desa :selected').val();
                console.log(desa);
                if(desa != []){
                  $('#namaDesa').html("Desa Adat "+ $('#desa :selected').text());
                }else{
                  $('#namaDesa').html($('#desa :selected').text())
                }
                
                $.ajax({
                    method : 'POST',
                    url : '{{route("jadwal-search")}}',
                    data : {
                    "_token" : "{{ csrf_token() }}",
                    id : id,
                    },
                    beforeSend : function() {
                                
                    },
                    success : (res) => {
                        console.log(res);
                        table.clear();
                        jQuery.each(res, function(i, val){
                            console.log(val);
                            table.row.add([
                              val.hari,
                              val.mulai,
                              val.selesai,
                              val.jenis_sampah
                            ]);
                        });
                        table.draw();
                    }
                });
            });
        });
      </script>
    </body>
  </html>
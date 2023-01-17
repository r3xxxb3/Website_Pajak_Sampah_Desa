@extends('layouts.auth-master')

@section('content')
<div class="card card-success">
  <div class="card-header"><h4>Masuk</h4></div>

  <div class="card-body">
    <form method="POST" action="{{ route('admin-authenticate') }}">
    @csrf
      <div class="form-group">
        <label for="username">Username/No telp/NIK<i class="text-danger text-sm text-bold">*</i></label>
        <input  id="username" type="username" class="form-control{{ $errors->login->has('username') ? ' is-invalid' : '' }}" name="username" placeholder="Masukan Username\No telp\NIK" tabindex="1" value="{{ old('username') }}" autofocus>
        <div class="invalid-feedback">
          {{ $errors->login->first('username') }}
        </div>
        <!-- @if(App::environment('demo'))
        <small id="emailHelpBlock" class="form-text text-muted">
            Demo Email: admin@example.com
        </small>
        @endif -->
      </div>

      <div class="form-group">
        <div class="d-block">
            <label for="password" class="control-label">Password<i class="text-danger text-sm text-bold">*</i></label>
          <div class="float-right">
            <a href="{{ route('password.request') }}" class="text-small">
              Lupa Password?
            </a>
          </div>
        </div>
        <input aria-describedby="passwordHelpBlock" id="password" type="password" placeholder="Masukan password" class="form-control{{ $errors->has('password') ? ' is-invalid': '' }}" name="password" tabindex="2">
        <div class="invalid-feedback">
          {{ $errors->first('password') }}
        </div>
        @if(App::environment('demo'))
        <small id="passwordHelpBlock" class="form-text text-muted">
            Demo Password: 1234
        </small>
        @endif
      </div>

      <div class="form-group">
        <div class="custom-control custom-checkbox">
          <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember"{{ old('remember') ? ' checked': '' }}>
          <label class="custom-control-label" for="remember">Ingat Saya</label>
        </div>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-success btn-lg btn-block" tabindex="4">
          Masuk
        </button>
      </div>
    </form>
  </div>
</div>
<div class="mt-5 text-muted text-center">
  Don't have an account? <a href="{{ route('register') }}">Create One</a>
</div>
@endsection

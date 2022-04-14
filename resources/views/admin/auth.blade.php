@extends('layouts.auth-master')

@section('content')
<div class="card card-success">
  <div class="card-header"><h4>Login</h4></div>

  <div class="card-body">
    <form method="POST" action="{{ route('admin-authenticate') }}">
    @csrf
      <div class="form-group">
        <label for="username">Username</label>
        <input  id="username" type="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" placeholder="Registered Username" tabindex="1" value="{{ old('username') }}" autofocus>
        <div class="invalid-feedback">
          {{ $errors->first('username') }}
        </div>
        <!-- @if(App::environment('demo'))
        <small id="emailHelpBlock" class="form-text text-muted">
            Demo Email: admin@example.com
        </small>
        @endif -->
      </div>

      <div class="form-group">
        <div class="d-block">
            <label for="password" class="control-label">Password</label>
          <div class="float-right">
            <a href="{{ route('password.request') }}" class="text-small">
              Forgot Password?
            </a>
          </div>
        </div>
        <input aria-describedby="passwordHelpBlock" id="password" type="password" placeholder="Your account password" class="form-control{{ $errors->has('password') ? ' is-invalid': '' }}" name="password" tabindex="2">
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
          <label class="custom-control-label" for="remember">Remember Me</label>
        </div>
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-success btn-lg btn-block" tabindex="4">
          Login
        </button>
      </div>
    </form>
  </div>
</div>
<div class="mt-5 text-muted text-center">
  Don't have an account? <a href="{{ route('register') }}">Create One</a>
</div>
@endsection

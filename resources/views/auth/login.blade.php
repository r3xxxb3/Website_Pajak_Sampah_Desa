@extends('layouts.auth-master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card card-success">
            <div class="card-header"><h4>Login</h4></div>

            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username<i class="text-danger text-sm text-bold">*</i></label>
                    <input  id="username" type="text" class="form-control{{ $errors->login->has('username') ? ' is-invalid' : '' }}" name="username" placeholder="Masukan Username" tabindex="1" value="{{ old('username') }}" autofocus>
                    
                    <span class="invalid-feedback">
                        <strong>
                            {{ $errors->login->first('username') }}
                        </strong>
                    </span>
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
                        Forgot Password?
                        </a>
                    </div>
                    </div>
                    <input aria-describedby="passwordHelpBlock" id="password" type="password" placeholder="Your account password" class="form-control{{ $errors->login->has('password') ? ' is-invalid': '' }}" name="password" tabindex="2">
                    <div class="invalid-feedback">
                    {{ $errors->login->first('password') }}
                    </div>
                    <!-- @if(App::environment('demo'))
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        Demo Password: 1234
                    </small>
                    @endif -->
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
            Don't have an account? <a href="{{ route('register-page') }}">Create One</a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('dashboard.auth')

@section('title')
{{ucwords('Login')}}
@endsection

@section('content')
<form class="m-login__form m-form" method="POST" action="{{ route('login') }}">
    @if (session('status'))
        <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-primary alert-dismissible fade show" role="alert">
            <div class="m-alert__icon">
                <i class="far fa-check-circle"></i>
                <span></span>
            </div>
            <div class="m-alert__text">
                <strong>{{ session('status') }}</strong>
            </div>
            <div class="m-alert__close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-success alert-dismissible fade show" role="alert">
            <div class="m-alert__icon">
                <i class="far fa-check-circle"></i>
                <span></span>
            </div>
            <div class="m-alert__text">
                <strong>{{ session('success') }}</strong>
            </div>
            <div class="m-alert__close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-danger alert-dismissible fade show" role="alert">
            <div class="m-alert__icon">
                <i class="fas fa-exclamation-triangle"></i>
                <span></span>
            </div>
            <div class="m-alert__text">
                <strong>{{ session('warning') }}</strong>
            </div>
            <div class="m-alert__close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @csrf
    <div class="form-group m-form__group">
        <input class="form-control m-input {{ $errors->has('email') ? ' is-invalid' : '' }}"  id="email" type="email" placeholder="Email" name="email" value="{{ old('email') }}" autofocus autocomplete="off">
        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group m-form__group">
        <input class="form-control m-input m-login__form-input--last {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" id="password" placeholder="Password" name="password">
        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>
    <div class="row m-login__form-sub">
        <div class="col m--align-left m-login__form-left">
            <label class="m-checkbox m-checkbox--solid m-checkbox--state-brand">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                Remember me
                <span></span>
            </label>
        </div>
        <div class="col m--align-right m-login__form-right">
            <!--<a href="{{ route('password.request') }}" id="m_login_forget_password" class="m-link">-->
            <!--    Forgot Your Password?-->
            <!--</a>-->
        </div>
    </div>
    <div class="m-login__form-action">
        <button type="submit" id="m_login_signin_submit" class="btn btn-primary m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn" style="width:100%;">
            Login
        </button>
    </div>
</form>
</div>
@endsection

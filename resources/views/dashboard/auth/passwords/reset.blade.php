@extends('dashboard.auth')

@section('title')
{{ucwords('Reset Password')}}
@endsection

@section('content')
<div class="m-login__signup" style="display:block;">
    <div class="m-login__head">
        <h3 class="m-login__title">
            Reset Password
        </h3>
    </div>
    <form class="m-login__form m-form" method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group m-form__group">
            <input class="form-control m-input {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" type="email" placeholder="Email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="off">
             @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group m-form__group">
            <input class="form-control m-input {{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" type="password" placeholder="Password" name="password" required>
            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group m-form__group">
            <input class="form-control m-input m-login__form-input--last" id="password-confirm" type="password" placeholder="Confirm Password" name="password_confirmation" required>
        </div>

        <div class="m-login__form-action">
            <button type="submit" id="m_login_signup_submit" class="btn btn-primary m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</div>
@endsection

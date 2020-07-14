@extends('dashboard.auth')

@section('title')
{{ucwords('Forgot Password')}}
@endsection

@section('content')
<div class="m-login__forget-password" style="display:block;">
    <div class="m-login__head">
        <h3 class="m-login__title">
            Forgotten Password ?
        </h3>
        <div class="m-login__desc">
            Enter your email to reset your password:
        </div>
    </div>
    

    <form class="m-login__form m-form" method="POST" action="{{ route('password.email') }}">
        @csrf
        @if (session('status'))
            {{-- <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div> --}}
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
        <div class="form-group m-form__group">
            <input class="form-control m-input {{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" type="email" placeholder="Email" name="email" value="{{ old('email') }}"  autocomplete="off">
            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="m-login__form-action">
            <button type="submit"  id="m_login_forget_password_submit" class="btn btn-primary m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">
                Request
            </button>
            &nbsp;&nbsp;
            <a href="{{url('/login')}}" id="m_login_forget_password_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom  m-login__btn">
                    Cancel
            </a>
        </div>
    </form>
</div>
@endsection

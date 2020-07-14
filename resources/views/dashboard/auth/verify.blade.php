@extends('dashboard.master')

@section('title')
{{ucwords('Verification')}}
@endsection

@section('main_content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
 
    <div class="m-content">
        <div class="row">
            <div class="col-xl-12">           
                <div class="m-portlet m-portlet--primary m-portlet--head-solid-bg">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon">
                                    <i class="fas fa-user-shield"></i>
                                </span>
                                <h3 class="m-portlet__head-text">
                                    {{ __('Verify Your Email Address') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        @if (session('resent'))
                            {{-- <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div> --}}
                                <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-primary alert-dismissible fade show" role="alert">
                                <div class="m-alert__icon">
                                    <i class="far fa-check-circle"></i>
                                    <span></span>
                                </div>
                                <div class="m-alert__text">
                                    <strong>{{ __('A fresh verification link has been sent to your email address.') }}</strong>
                                </div>
                                <div class="m-alert__close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        @endif
                        
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }},<b> <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                        </b>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

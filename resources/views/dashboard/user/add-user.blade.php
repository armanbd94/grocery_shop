@extends('dashboard.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('main_content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto col-md-12">
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="{{url('/')}}" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                            <span class="m-nav__link-text">
                                Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="m-nav__item">
                        <i class="la la-angle-double-right"></i>
                        <span class="m-nav__link-text">
                            <i class="fas {{$page_icon}}"></i> {{$page_title}}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->    

    <!-- BEGIN: Main Content Section -->
    <div class="m-content">
        <div class="row">
            <div class="col-xl-12">
                <div class="m-portlet m-portlet--creative m-portlet--bordered-semi">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                    <span><i class="fas {{$page_icon}}"></i> {{$page_title}}</span>
                                </h2>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                   <a href="{{url('/user')}}" class="btn btn-primary m-btn m-btn--air m-btn--custom back-btn">
                                    <i class="fas fa-reply-all"></i> Back
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        @if (session('mail_sent'))
                                <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-primary alert-dismissible fade show" role="alert">
                                <div class="m-alert__icon">
                                    <i class="far fa-check-circle"></i>
                                    <span></span>
                                </div>
                                <div class="m-alert__text">
                                    <strong>{{session('mail_sent')}}</strong>
                                </div>
                                <div class="m-alert__close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('register') }}" class="m-form m-form--fit m-form--label-align-right" id="update_profile">
                            <div class="m-portlet__body">
                                {{ csrf_field() }}
                                <div class="form-group m-form__group">
                                    <label for="example-text-input">
                                        Name
                                    </label>
                                    <input class="form-control m-input col-md-7 {{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif  
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="example-text-input">
                                        Email
                                    </label>
                                    <input class="form-control m-input col-md-7 {{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" name="email">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif   
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="example-text-input">
                                        Mobile No.
                                    </label>
                                    <input class="form-control m-input col-md-7 {{ $errors->has('mobile_no') ? ' is-invalid' : '' }}" type="text" name="mobile_no" >
                                    @if ($errors->has('mobile_no'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('mobile_no') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="form-group m-form__group">
                                    <label for="example-text-input">
                                        Additional Mobile No.
                                    </label>
                                    <input class="form-control m-input col-md-7" type="text" name="additional_mobile_no">
                                </div>

                                <div class="form-group m-form__group">
                                    <label for="example-text-input">
                                        Gender
                                    </label>
                                    <select class="form-control m-input col-md-7 {{ $errors->has('gender') ? ' is-invalid' : '' }}" name="gender" >
                                        <option value="">Select Please</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                    </select>
                                    @if ($errors->has('gender'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('gender') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group m-form__group">
                                    <label for="example-text-input">
                                        Password
                                    </label>
                                    <input class="form-control m-input col-md-7 {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" name="password">
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group m-form__group">
                                    <label for="example-text-input">
                                        Confirm Password
                                    </label>
                                    <input class="form-control m-input col-md-7" type="password" name="password_confirmation">                                    
                                </div>

                                <div class="form-group m-form__group">
                                    <label for="example-text-input">
                                        Role
                                    </label>
                                    <select class="form-control m-input col-md-7 {{ $errors->has('role_id') ? ' is-invalid' : '' }}" name="role_id" >
                                        <option value="">Select Please</option>
                                        @if(count($roles) > 0)
                                            @foreach ($roles as $role)
                                                <option value="{{$role->id}}">{{$role->role_name}}</option>
                                            @endforeach
                                        @endif
                                        
                                    </select>
                                    @if ($errors->has('role_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('role_id') }}</strong>
                                        </span>
                                    @endif                                    
                                </div>

                                <div class="form-group m-form__group">
                                    <label for="example-text-input">
                                        Address
                                    </label>
                                    <textarea class="form-control m-input col-md-7" name="address"></textarea>                                   
                                </div>
                                <div class="form-group m-form__group">
                                    <button type="submit" class="btn btn-primary m-btn m-btn--air m-btn--custom">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                    &nbsp;&nbsp;
                                    <button type="reset" class="btn btn-close m-btn m-btn--air m-btn--custom">
                                       <i class="fas fa-sync-alt"></i> Reset
                                    </button>
                                        
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN: Main Content Section -->

</div>

<script type="text/javascript">

</script>

@endsection

@extends('dashboard.master')

@section('title')
{{$page_title}}
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
                            <i class="{{$page_icon}}"></i> {{$page_title}}
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
                                    <span><i class="{{$page_icon}}"></i> {{$page_title}}</span>
                                </h2>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                   <a href="{{url('/message')}}" class="btn btn-primary m-btn m-btn--air m-btn--custom back-btn">
                                    <i class="fas fa-reply-all"></i> Back
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                       <div class="row pt-20">
                            <div class="col-lg-12 pb-20">
                                <table class="table table-striped"  width="100%">
                                    <tbody>
                                        <tr>
                                            <td width="10%"><b>Name</b></td>
                                            <td><b>: </b>{{$message->name}}</td>
                                        </tr>
                                        <tr>
                                            <td width="10%"><b>Subject</b></td>
                                            <td><b>: </b>{{$message->subject}}</td>
                                        </tr>
                                        <tr>
                                            <td width="10%"><b>Email</b></td>
                                            <td><b>: </b>{{$message->email}}</td>
                                        </tr>
                                        <tr>
                                            <td width="10%"><b>Sent Date</b></td>
                                            <td><b>: </b>{{Helper::date_time($message->created_at)}}</td>
                                        </tr>
                                        @if (Auth::user()->role_id == 1)
                                        <tr>
                                            <td width="10%"><b>Status</b></td>
                                            <td><b>: </b><span class="m-badge {{json_decode(STATUS_TYPE)[$message->status]}}  m-badge--wide">{{json_decode(MSG_STATUS)[$message->status]}}</span></td>
                                        </tr>
                                        <tr>
                                            <td width="10%"><b>Seen By</b></td>
                                            <td><b>: </b>{{$message->user_name}}</td>
                                        </tr>
                                        @endif 
                                        <tr>
                                            <td width="10%"><b>Message</b></td>
                                            <td><b>: </b>{{$message->message}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                                           
                            </div>

                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN: Main Content Section -->

</div>


@endsection


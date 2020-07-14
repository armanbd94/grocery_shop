@extends('dashboard.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('main_content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN:: SUBHEADER SECTION -->
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
    <!-- END:: SUBHEADER SECTION -->    

    <!-- BEGIN:: MAIN CONTENT SECTION -->
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
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <!-- BEGIN:: DATATABLE SEARCH DATA FORM SECTION -->
                        <form method="POST" id="time-slot-form" class="m-form m-form--fit m--margin-bottom-20" role="form">
                           @csrf
                            <div class="row m--margin-bottom-20">
                                <div class="col-lg-3 col-sm-8 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Time Duration(minute):</label>
                                    <input type="text" class="form-control d=" name="time_duration" id="time_duration" value="{{$data->time_duration}}" placeholder="Enter role name" />
                                   <span class="error error_time_duration text-danger"></span>
                                </div>
                                <div class="col-lg-3 col-sm-8 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Start Time:</label>
                                    <input type="text" class="form-control timepicker" name="start_time" id="start_time"  value="{{$data->start_time}}" placeholder="Enter role name" />
                                    <span class="error error_start_time text-danger"></span>
                                </div>
                                <div class="col-lg-3 col-sm-8 m--margin-bottom-10-tablet-and-mobile">
                                    <label>End Time:</label>
                                    <input type="text" class="form-control timepicker" name="end_time" id="end_time" value="{{$data->end_time}}" placeholder="Enter role name" />
                                    <span class="error error_end_time text-danger"></span>
                                </div>

                                <div class="col-lg-3 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label></label>
                                    <div style="margin-top: 7px;">
                                        <button id="btn-reset" class="btn btn-danger  dim reset-btn pull-right" data-toggle="tooltip"
                                            data-placement="top" title="" data-original-title="Reset" type="reset"><i
                                                class="fas fa-refresh"></i> Reset</button>

                                        <button id="save-btn" class="btn dim search-btn pull-right"  type="button"><i
                                                class="fas fa-save"></i> Save</button>
                                        
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END:: DATATABLE SEARCH DATA FORM SECTION -->

                        <!-- BEGIN:: ROLE TABLE SECTION -->
                        <div class="table-responsive">
                            <table class="table table-striped- table-bordered table-hover table-checkable roleTable" id="time_slot_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Time Slot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {!! $time_slot !!}
                                </tbody>
                            </table>
                        </div>
                        <!-- END:: ROLE TABLE SECTION -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END:: MAIN CONTENT SECTION -->


</div>

<!-- BEGIN:: JS SCRIPT SECTION -->
<script src="{{asset('public/dashboard-assets/demo/default/custom/crud/forms/widgets/bootstrap-timepicker.js')}}"></script>
<script src="{{asset('public/dashboard-assets/demo/default/custom/crud/forms/widgets/bootstrap-touchspin.js')}}"></script>
<script>
$(document).ready(function () {


    $('#time_duration').TouchSpin({
            buttondown_class: "btn btn-secondary",
            buttonup_class: "btn btn-secondary",
            min: 1,
            max: 100000,
            step: 1,
    });
    $('.timepicker').timepicker({
        minuteStep: 1,
        showSeconds: !1,
    });
     /** BEGIN:: ROLE DATA ADD/UPDATE AJAX CODE **/
    $('#save-btn').click(function () {

        $.ajax({
            url: '{{url("store-time-slot")}}',
            type: "POST",
            data: $('#time-slot-form').serialize(),
            dataType: "JSON",
            beforeSend: function () {
                $('#save-btn').addClass('m-loader m-loader--light m-loader--left');
            },
            complete: function() {
                $('#save-btn').removeClass('m-loader m-loader--light m-loader--left');
            },
            success: function (data) {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": true,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };
                if (data.success) {
                    console.log(data);
                    toastr.success(data.success, "SUCCESS");
                    // $('#time-slot-form')[0].reset();
                    get_time_slot();
                } else if(data.error){
                    toastr.error(data.error, "ERROR");
                }else {

                    $("#time-slot-form").find('.error').text('');
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('#time-slot-form').find('.error_'+key).text(value); 
                    });
                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    });
    /** END:: ROLE DATA ADD/UPDATE AJAX CODE **/
}); 

function get_time_slot(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url:'{{url("time-slot-list")}}',
        type: "POST",
        success: function (data) {
            $('#time_slot_table tbody').html('');
            $('#time_slot_table tbody').html(data);
        }
    });
}
</script>
<!-- END:: JS SCRIPT SECTION -->
@endsection

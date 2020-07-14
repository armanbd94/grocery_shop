@extends('dashboard.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('main_content')
<link href="{{asset('public/dashboard-assets/vendors/custom/croppier/croppie.css')}}" rel="stylesheet" type="text/css" /> <!-- :: CROPPIE CSS FOR CROP IMAGE :: -->

<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: SUBHEADER SECTION -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto col-md-12">
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item">
                        <span class="m-nav__link-text">
                            <i class="fas {{$page_icon}}"></i> {{$page_title}} 
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: SUBHEADER SECTION -->

   <!-- BEGIN: MAIN CONTENT SECTION -->
    <div class="m-content">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="m-portlet m-portlet--full-height  ">
                    <div class="m-portlet__body">

                        <div class="m-card-profile">
                            <div class="m-card-profile__title m--hide">
                                My Profile
                            </div>

                            <!-- BEGIN:: PROFILE PHOTO SECTION -->
                            <div class="m-card-profile__pic">
                                <div class="m-card-profile__pic-wrapper">
                                    <img src="{{asset('')}}{{Helper::profile_photo()}}" alt="" />
                                    <div id="camera_icon"><i class="fas fa-camera"></i></div>
                                </div>
                            </div>
                            <!-- END:: PROFILE PHOTO SECTION -->

                            <!-- BEGIN:: USER NAME & ROLE SECTION -->
                            <div class="m-card-profile__details">
                                <span class="m-card-profile__name">
                                    {{Auth::user()->name}}
                                </span>
                                <a href="" class="m-card-profile__email m-link">
                                    <b>{{Helper::auth_role_name()}}</b>
                                </a>
                            </div>
                            <!-- END:: USER NAME & ROLE SECTION -->
                        </div>
                        {{-- <ul class="m-nav m-nav--hover-bg m-portlet-fit--sides">
                            <li class="m-nav__separator m-nav__separator--fit"></li>
                            <li class="m-nav__section m--hide">
                                <span class="m-nav__section-text">
                                    Section
                                </span>
                            </li>
                            <li class="m-nav__item">
                                <a href="../header/profile&amp;demo=default.html" class="m-nav__link">
                                    <i class="m-nav__link-icon flaticon-profile-1"></i>
                                    <span class="m-nav__link-title">
                                        <span class="m-nav__link-wrap">
                                            <span class="m-nav__link-text">
                                                My Profile
                                            </span>
                                            <span class="m-nav__link-badge">
                                                <span class="m-badge m-badge--success">
                                                    2
                                                </span>
                                            </span>
                                        </span>
                                    </span>
                                </a>
                            </li>
                            <li class="m-nav__item">
                                <a href="../header/profile&amp;demo=default.html" class="m-nav__link">
                                    <i class="m-nav__link-icon flaticon-share"></i>
                                    <span class="m-nav__link-text">
                                        Activity
                                    </span>
                                </a>
                            </li>
                            <li class="m-nav__item">
                                <a href="../header/profile&amp;demo=default.html" class="m-nav__link">
                                    <i class="m-nav__link-icon flaticon-chat-1"></i>
                                    <span class="m-nav__link-text">
                                        Messages
                                    </span>
                                </a>
                            </li>
                            <li class="m-nav__item">
                                <a href="../header/profile&amp;demo=default.html" class="m-nav__link">
                                    <i class="m-nav__link-icon flaticon-graphic-2"></i>
                                    <span class="m-nav__link-text">
                                        Sales
                                    </span>
                                </a>
                            </li>
                            <li class="m-nav__item">
                                <a href="../header/profile&amp;demo=default.html" class="m-nav__link">
                                    <i class="m-nav__link-icon flaticon-time-3"></i>
                                    <span class="m-nav__link-text">
                                        Events
                                    </span>
                                </a>
                            </li>
                            <li class="m-nav__item">
                                <a href="../header/profile&amp;demo=default.html" class="m-nav__link">
                                    <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                    <span class="m-nav__link-text">
                                        Support
                                    </span>
                                </a>
                            </li>
                        </ul> --}}

                        <div class="m-portlet__body-separator"></div> <!--:: SEPARATOR BETWEEN TWO SECTION ::-->   

                        <!-- BEGIN:: TAB NAV SECTION -->
                        <ul class="nav m-tabs m-tabs-line m-nav--hover-bg m-portlet-fit--sides" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_user_profile_tab_1"
                                    role="tab">
                                    <i class="fas fa-user-circle"></i>
                                    My Profile
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_user_profile_tab_2"
                                    role="tab">
                                    <i class="fas fa-key"></i>
                                    Change Password
                                </a>
                            </li>
                        </ul>
                        <!-- END:: TAB NAV SECTION -->

                        <div class="m-portlet__body-separator"></div> <!--:: SEPARATOR BETWEEN TWO SECTION ::-->   

                        <div class="m-widget1 m-widget1--paddingless">
                            <div class="m-widget1__item">
                                <div class="row m-row--no-padding align-items-center">
                                    <div class="col text-center">
                                        <h3 class="m-widget1__title ">
                                            Joined Since
                                        </h3>
                                        <span class="m-widget1__desc">
                                            <b>{{Helper::date_time(Auth::user()->created_at)}}</b>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="m-widget1__item">
                                <div class="row m-row--no-padding align-items-center">
                                    <div class="col">
                                        <h3 class="m-widget1__title">
                                            Orders
                                        </h3>
                                        <span class="m-widget1__desc">
                                            Weekly Customer Orders
                                        </span>
                                    </div>
                                    <div class="col m--align-right">
                                        <span class="m-widget1__number m--font-danger">
                                            +1,800
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="m-widget1__item">
                                <div class="row m-row--no-padding align-items-center">
                                    <div class="col">
                                        <h3 class="m-widget1__title">
                                            Issue Reports
                                        </h3>
                                        <span class="m-widget1__desc">
                                            System bugs and issues
                                        </span>
                                    </div>
                                    <div class="col m--align-right">
                                        <span class="m-widget1__number m--font-success">
                                            -27,49%
                                        </span>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="m-portlet m-portlet--full-height m-portlet--tabs  ">
                    {{-- <div class="m-portlet__head">
                        <div class="m-portlet__head-tools">
                            <ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary" role="tablist">
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_user_profile_tab_1"
                                        role="tab">
                                        <i class="flaticon-share m--hide"></i>
                                        Update Profile
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_user_profile_tab_2"
                                        role="tab">
                                        Change Password
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item m-portlet__nav-item--last">
                                    <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push"
                                        m-dropdown-toggle="hover" aria-expanded="true">
                                        <a href="#" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
                                            <i class="la la-gear"></i>
                                        </a>
                                        <div class="m-dropdown__wrapper">
                                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                            <div class="m-dropdown__inner">
                                                <div class="m-dropdown__body">
                                                    <div class="m-dropdown__content">
                                                        <ul class="m-nav">
                                                            <li class="m-nav__section m-nav__section--first">
                                                                <span class="m-nav__section-text">
                                                                    Quick Actions
                                                                </span>
                                                            </li>
                                                            <li class="m-nav__item">
                                                                <a href="" class="m-nav__link">
                                                                    <i class="m-nav__link-icon flaticon-share"></i>
                                                                    <span class="m-nav__link-text">
                                                                        Create Post
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="m-nav__item">
                                                                <a href="" class="m-nav__link">
                                                                    <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                    <span class="m-nav__link-text">
                                                                        Send Messages
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="m-nav__item">
                                                                <a href="" class="m-nav__link">
                                                                    <i class="m-nav__link-icon flaticon-multimedia-2"></i>
                                                                    <span class="m-nav__link-text">
                                                                        Upload File
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="m-nav__section">
                                                                <span class="m-nav__section-text">
                                                                    Useful Links
                                                                </span>
                                                            </li>
                                                            <li class="m-nav__item">
                                                                <a href="" class="m-nav__link">
                                                                    <i class="m-nav__link-icon flaticon-info"></i>
                                                                    <span class="m-nav__link-text">
                                                                        FAQ
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="m-nav__item">
                                                                <a href="" class="m-nav__link">
                                                                    <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                    <span class="m-nav__link-text">
                                                                        Support
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="m-nav__separator m-nav__separator--fit m--hide"></li>
                                                            <li class="m-nav__item m--hide">
                                                                <a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">
                                                                    Submit
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                    
                    <!-- BEGIN:: TAB CONTENT -->
                    <div class="tab-content">   
                        <!-- BEGIN:: PROFILE DATA FORM -->                    
                        <div class="tab-pane active" id="m_user_profile_tab_1">
                            <form class="m-form m-form--fit m-form--label-align-right" id="update_profile">
                                <div class="m-portlet__body">
                                   {{ csrf_field() }}
                                    <div class="form-group m-form__group">
                                        <label for="example-text-input">
                                            Name
                                        </label>
                                        <input class="form-control m-input" type="text" name="name" value="{{Auth::user()->name}}">
                                        <span class="text-danger error error_name"></span>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="example-text-input">
                                            Email
                                        </label>
                                        <input class="form-control m-input" type="text" value="{{Auth::user()->email}}" readonly>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="example-text-input">
                                            Mobile No.
                                        </label>
                                        <input class="form-control m-input" type="text" name="mobile_no" value="{{Auth::user()->mobile_no}}">
                                        <span class="text-danger error error_mobile_no"></span>
                                    </div>
                                    
                                    <div class="form-group m-form__group">
                                        <label for="example-text-input">
                                            Additional Mobile No.
                                        </label>
                                        <input class="form-control m-input" type="text" name="additional_mobile_no" value="{{Auth::user()->additional_mobile_no}}">
                                    </div>

                                    <div class="form-group m-form__group">
                                        <label for="example-text-input">
                                           Gender
                                        </label>
                                        <select class="form-control m-input" name="gender" >
                                            <option value="">Select Please</option>
                                            <option <?php if(Auth::user()->gender == 1){ echo 'selected';} ?> value="1">Male</option>
                                            <option <?php if(Auth::user()->gender == 2){ echo 'selected';} ?> value="2">Female</option>
                                        </select>
                                        <span class="text-danger error error_gender"></span>
                                    </div>

                                   <div class="form-group m-form__group">
                                        <label for="example-text-input">
                                            Address
                                        </label>
                                        <textarea class="form-control m-input" name="address">{{Auth::user()->address}}</textarea>                                       
                                    </div>
                                    <div class="form-group m-form__group">
                                        <button type="button" class="btn btn-primary m-btn m-btn--air m-btn--custom" onclick="save_changes()">
                                            <i class="fas fa-save"></i> Save changes
                                        </button>
                                        &nbsp;&nbsp;
                                        <button type="reset" class="btn btn-close m-btn m-btn--air m-btn--custom">
                                            <i class="fas fa-sync-alt"></i> Reset
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="m-portlet__body-separator"></div>
                            </form>
                        </div>
                        <!-- END:: PROFILE DATA FORM -->     

                        <!-- BEGIN:: PASSWORD CHANGE FORM -->     
                        <div class="tab-pane " id="m_user_profile_tab_2">
                            <form class="m-form m-form--fit m-form--label-align-right" id="update_password">
                                <div class="m-portlet__body">
                                    {{ csrf_field() }}
                                    <div class="form-group m-form__group">
                                        <label for="example-text-input">
                                            New Password
                                        </label>
                                        <input class="form-control m-input" type="password" name="password">
                                        <span class="text-danger error error_password"></span>                                       
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="example-text-input">
                                            Confirm Password
                                        </label>
                                        <input class="form-control m-input" type="password" name="password_confirmation">
                                        <span class="text-danger error error_password_confirmation"></span>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <button type="button" class="btn btn-primary m-btn m-btn--air m-btn--custom" onclick="update_password()">
                                            <i class="fas fa-edit"></i> Update Password
                                        </button>
                                        &nbsp;&nbsp;
                                        <button type="reset" class="btn btn-close m-btn m-btn--air m-btn--custom">
                                            <i class="fas fa-sync-alt"></i> Reset
                                        </button>  
                                    </div>
                                </div>
                               
                            </form>
                        </div>
                        <!-- END:: PASSWORD CHANGE FORM -->   
                    </div>
                    <!-- END:: TAB CONTENT -->
                </div>
            </div>
        </div>
    </div>
    <!-- END: MAIN CONTENT SECTION -->

    <!-- BEGIN: UPLOAD & CROP PFOFILE PHOTO MODAL FORM -->
    <div class="modal fade" id="uploadimageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"><i class="fas fa-upload"></i> Upload & Crop Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input class="form-control" type="file" name="upload_image" id="upload_image" />
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div id="image_demo" style="width:100%; margin-top:30px"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-close" data-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary crop_image">
                        <i class="fas fa-upload"></i> Crop & Upload Image
                    </button>
                </div>
            </div>
        </div>
    </div>    
    <!-- END: UPLOAD & CROP PFOFILE PHOTO MODAL FORM -->
</div>
<!-- BEGIN: JS SCRIPT SECTION -->
<script src="{{asset('public/dashboard-assets/vendors/custom/croppier/croppie.js')}}" type="text/javascript"> </script> <!-- :: CROPPIE JS FOR CROP IMAGE :: -->

<script>
/* BEGIN: UPDATE USER DATA AJAX CODE */
function save_changes(){
    $.ajax({
        url: "{{url('/update-profile')}}",
        type: "POST",
        data: $('#update_profile').serialize(),
        dataType: "JSON",
        beforeSend: function () {
            $('.ajax_loading').show();
        },
        success: function (data) {
            if (data.success) {
                // console.log(data);
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
                toastr.success(data.success, "SUCCESS");
                $('.ajax_loading').hide();
                location.reload();
            } else {

                $("#update_profile").find('.error').text('');
                // console.log(data.errors);
                $.each(data.errors, function (key, value) {
                    $('.form-group').find('.error_' +
                        key).text(value);
                });

                $('.ajax_loading').hide();
            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error update data');
        }
    });
}
/* END: UPDATE USER DATA AJAX CODE */

/* BEGIN: UPDATE USER PASSWORD AJAX CODE */
function update_password(){
    $.ajax({
        url: "{{url('/update-password')}}",
        type: "POST",
        data: $('#update_password').serialize(),
        dataType: "JSON",
        beforeSend: function () {
            $('.ajax_loading').show();
        },
        success: function (data) {
            if (data.success) {
                // console.log(data);
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
                toastr.success(data.success, "SUCCESS");
                $('.ajax_loading').hide();
                location.reload();
            } else {

                $("#update_password").find('.error').text('');
                // console.log(data.errors);
                $.each(data.errors, function (key, value) {
                    $('.form-group').find('.error_' +
                        key).text(value);
                });

                $('.ajax_loading').hide();
            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error update data');
        }
    });
}
/* END: UPDATE USER PASSWORD AJAX CODE */

/* BEGIN: CROP & UPLOAD IMAGE AJAX CODE */
$(document).ready(function(){
    $("#camera_icon").click(function(){
        $("#uploadimageModal").modal('show');
    });
	$image_crop = $('#image_demo').croppie({
        enableExif: true,
        viewport: {
            width:220,
            height:220,
            type:'circle' //circle|square
        },
        boundary:{
            width:300,
            height:300
        }
    });

    $('#upload_image').on('change', function(){
        var reader = new FileReader();
        reader.onload = function (event) {
        $image_crop.croppie('bind', {
            url: event.target.result
        }).then(function(){
            console.log('jQuery bind complete');
        });
        }
        reader.readAsDataURL(this.files[0]);
        // $('#uploadimageModal').modal('show');
    });

    $('.crop_image').click(function(event){
        var _token = "{{csrf_token()}}";
        $image_crop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function(response){
            $.ajax({
                url: "{{url('/add-profile-photo')}}",
                type: "POST",
                data:{"image": response,_token:_token},
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
                        toastr.success(data.success, "SUCCESS");
                        $("#uploadimageModal").modal('hide');
                        location.reload();
                    } else {
                        toastr.error(data.error, "ERROR");
                        $("#uploadimageModal").modal('show');
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error update profile photo');
                }
            });
        })
    });

}); 
/* BEGIN: CROP & UPLOAD IMAGE AJAX CODE */
</script>

@endsection

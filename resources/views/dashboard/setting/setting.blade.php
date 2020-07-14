@extends('dashboard.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('main_content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: SUBHEADER SECTION -->
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
    <!-- END: SUBHEADER SECTION -->

   <!-- BEGIN: MAIN CONTENT SECTION -->
    <div class="m-content">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="m-portlet m-portlet--full-height  ">
                    <div class="m-portlet__body">

                        <!-- BEGIN:: TAB NAV SECTION -->
                        <ul class="nav m-tabs m-tabs-line m-nav--hover-bg m-portlet-fit--sides setting_tab" role="tablist">
                            @if(in_array('General Setting',session()->get('permission')))
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#setting"
                                    role="tab">
                                    <i class="fas fa-wrench"></i>
                                    General Setting
                                </a>
                            </li>
                            @endif
                            @if(in_array('Mail Setting',session()->get('permission')))
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#smtp_setting"
                                    role="tab">
                                    <i class="fas fa-envelope"></i>
                                    SMTP Setting
                                </a>
                            </li>
                            @endif
                        </ul>
                        <!-- END:: TAB NAV SECTION -->
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8">
                <div class="m-portlet m-portlet--full-height m-portlet--tabs  ">
                    <!-- BEGIN:: TAB CONTENT -->
                    <div class="tab-content">   
                        <!-- BEGIN:: SETTING DATA -->                    
                        <div class="tab-pane active" id="setting">
                            <form class="m-form m-form--fit m-form--label-align-right" id="update_site_settings" method="POST"  enctype="multipart/form-data">
                                <div class="m-portlet__body">
                                    @csrf
                                    <div class="form-group m-form__group">
                                        <label for="website_title">
                                            Title:
                                        </label>
                                        <input class="form-control m-input" type="text" name="website_title" id="website_title">
                                        <span class="text-danger error error_website_title"></span>   
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="website_footer_text">
                                            Website Footer Text:
                                        </label>
                                        <input class="form-control m-input" type="text" name="website_footer_text" id="website_footer_text">
                                        <span class="text-danger error error_website_footer_text"></span>   
                                    </div>
                                     <div class="form-group m-form__group">
                                        <label for="contact_email">
                                            Contact Email Address:
                                        </label>
                                        <input class="form-control m-input" type="text" name="contact_email" id="contact_email">
                                        <span class="text-danger error error_contact_email"></span>   
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="contact_no">
                                            Contact No.:
                                        </label>
                                        <input class="form-control m-input" type="text" name="contact_no" id="contact_no">
                                        <span class="text-danger error error_contact_no"></span>   
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="contact_address">
                                            Contact Address:
                                        </label>
                                        <textarea class="form-control m-input" name="contact_address" id="contact_address"></textarea>
                                        <span class="text-danger error error_contact_address"></span>   
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="contact_address">
                                            Invoice Prefix:
                                        </label>
                                        <input type="text" class="form-control m-input" name="invoice_prefix" id="invoice_prefix" />
                                        <span class="text-danger error error_invoice_prefix"></span>   
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="shipping_fee">
                                            Shipping Fee:
                                        </label>
                                        <input type="text" class="form-control m-input" name="shipping_fee" id="shipping_fee" />
                                        <span class="text-danger error error_shipping_fee"></span>   
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="min_order">
                                            Minimum Order(QAR):
                                        </label>
                                        <input type="text" class="form-control m-input" name="min_order" id="min_order" />
                                        <span class="text-danger error error_min_order"></span>   
                                    </div>
                                    
                                    <div class="form-group m-form__group">
                                        <label for="site_logo">
                                            Site Logo:
                                        </label>
                                        <input class="form-control m-input" type="file" name="site_logo" >
                                        <span class="text-danger error error_site_logo"></span>   
                                    </div>
                                    <div class="form-group m-form__group old-logo">
                                        <div class="form-control" id="old-logo"></div>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="footer_logo">
                                            Footer Logo:
                                        </label>
                                        <input class="form-control m-input" type="file" name="footer_logo" >
                                        <span class="text-danger error error_footer_logo"></span>   
                                    </div>
                                    <div class="form-group m-form__group old-footer-logo">
                                        <div class="form-control" id="old-footer-logo"></div>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="favicon_icon">
                                            Site Favicon Icon:
                                        </label>
                                        <input class="form-control m-input" type="file" name="favicon_icon" >
                                        <span class="text-danger error error_favicon_icon"></span>   
                                    </div>                                   
                                    <div class="form-group m-form__group old-icon">
                                        <div class="form-control" id="old-icon"></div>
                                    </div>

                                    <div class="form-group m-form__group">
                                        <label for="facebook">
                                            Facebook URL:
                                        </label>
                                        <input class="form-control m-input" type="text" name="facebook" id="facebook">  
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="twitter">
                                            Twitter URL:
                                        </label>
                                        <input class="form-control m-input" type="text" name="twitter" id="twitter">  
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="linkedin">
                                            Linkedin URL:
                                        </label>
                                        <input class="form-control m-input" type="text" name="linkedin" id="linkedin">  
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="linkedin">
                                            YouTube URL:
                                        </label>
                                        <input class="form-control m-input" type="text" name="youtube" id="youtube">  
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="skype">
                                            Skype Username:
                                        </label>
                                        <input class="form-control m-input" type="text" name="skype" id="skype">  
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="instagram">
                                            Instagram URL:
                                        </label>
                                        <input class="form-control m-input" type="text" name="instagram" id="instagram">  
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
                               
                                <div class="m-portlet__body-separator"></div>
                            </form>
                        </div>
                        <!-- END:: SETTING DATA -->     

                        <!-- BEGIN:: SMTP SETTING -->     
                        <div class="tab-pane " id="smtp_setting">
                            <form class="m-form m-form--fit m-form--label-align-right" method="POST" id="update_mail_setting">
                                <div class="m-portlet__body">
                                    {{ csrf_field() }}
                                    <div class="form-group m-form__group">
                                        <label for="mail_protocol">
                                            Protocol:
                                        </label>
                                        <select class="form-control m-input" name="mail_protocol" id="mail_protocol">
                                            <option value="">Select Please</option>
                                            <option value="smtp">SMTP</option>
                                            <option value="sendmail">SENDMAIL</option>
                                            <option value="mail">MAIL</option>
                                        </select>
                                        <span class="text-danger error error_mail_protocol"></span>   
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="mail_hostname">
                                            Hostname:
                                        </label>
                                        <input class="form-control m-input" type="text" name="mail_hostname" id="mail_hostname" >
                                        <span class="text-danger error error_mail_hostname"></span>   
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="mail_port">
                                            Port:
                                        </label>
                                        <select class="form-control m-input" name="mail_port" id="mail_port">
                                            <option value="">Select Please</option>
                                            <option value="25">25</option>
                                            <option value="465">465</option>
                                            <option value="587">587</option>
                                        </select>
                                        <span class="text-danger error error_mail_port"></span>   
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="mail_username">
                                            Username:
                                        </label>
                                        <input class="form-control m-input" type="text" name="mail_username" id="mail_username" >
                                        <span class="text-danger error error_mail_username"></span>   
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="mail_password">
                                            Password:
                                        </label>
                                        <input class="form-control m-input" type="text" name="mail_password" id="mail_password" >
                                        <span class="text-danger error error_mail_password"></span>   
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label for="mail_security">
                                            Security:
                                        </label>
                                        <select class="form-control m-input" name="mail_security" id="mail_security">
                                            <option value="">Select Please</option>
                                            <option value="tls">TLS</option>
                                            <option value="ssl">SSL</option>
                                        </select>
                                        <span class="text-danger error error_mail_security"></span>   
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
                                <div class="m-portlet__body-separator"></div>
                            </form>
                        </div>
                        <!-- END:: SMTP SETTING--> 
                    </div>
                    <!-- END:: TAB CONTENT -->
                </div>
            </div>
        </div>
    </div>
    <!-- END: MAIN CONTENT SECTION -->


</div>
<!-- BEGIN: JS SCRIPT SECTION -->
<script src="{{asset('public/dashboard-assets/vendors/custom/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
<script>
tinymce.init({
    selector: 'textarea.editable'
});
</script>
<script>
$(document).ready(function () {
    $(".old-logo").hide();
    $(".old-footer-logo").hide();
    $(".old-icon").hide();
    $('.content-loader').hide();
    get_site_data();
    
    $('#update_site_settings').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:"{{url('/store-site-data')}}",
            type: "POST",
            data: new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            beforeSend: function () {
                $('.ajax_loading').show();
            },
            success: function (data)
            {
                console.log(data);
                if (data.success) {
                    
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
                    toastr.success(data.success,"SUCCESS");
                    $('.ajax_loading').hide();
                    $("#update_site_settings").find('.error').text(''); 
                    get_site_data();
                } else
                {
                
                    $("#update_site_settings").find('.error').text(''); 
                    $.each(data.errors, function (key, value) {
                        $('.form-group').find('.error_' +
                            key).text(value);
                    });
                    $('.ajax_loading').hide();

                }


            },
            error: function (jqXHR, textStatus, errorThrown)
            {
            alert('Error update data');
            $('.ajax_loading').hide();
            }
        });
    });

    $('#update_mail_setting').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:"{{url('/store-mail-data')}}",
            type: "POST",
            data: $('#update_mail_setting').serialize(),
            dataType: "JSON",
            beforeSend: function () {
                $('.ajax_loading').show();
            },
            success: function (data)
            {
                console.log(data);
                if (data.success) {
                    
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
                    toastr.success(data.success,"SUCCESS");
                    $('.ajax_loading').hide();
                    $("#update_mail_setting").find('.error').text(''); 
                    get_site_data();
                } else
                {
                
                    $("#update_mail_setting").find('.error').text(''); 
                    $.each(data.errors, function (key, value) {
                        $('.form-group').find('.error_' +
                            key).text(value);
                    });
                    $('.ajax_loading').hide();

                }


            },
            error: function (jqXHR, textStatus, errorThrown)
            {
            alert('Error update data');
            $('.ajax_loading').hide();
            }
        });
    });

});

function get_site_data(){
    $.ajax({
        url: "{{url('/setting-data')}}",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            console.log(data);
            $('#update_site_settings #website_title').val(data.setting.website_title);
            $('#update_site_settings #website_footer_text').val(data.setting.website_footer_text);
            $('#update_site_settings #contact_email').val(data.setting.contact_email);
            $('#update_site_settings #contact_no').val(data.setting.contact_no);
            $('#update_site_settings #contact_address').val(data.setting.contact_address);
            $('#update_site_settings #invoice_prefix').val(data.setting.invoice_prefix);
            $('#update_site_settings #shipping_fee').val(data.setting.shipping_fee);
            $('#update_site_settings #min_order').val(data.setting.min_order);
            

            if(data.setting.site_logo){
                $(".old-logo").show();
                $('#update_site_settings #old-logo').html("<img src='{{asset(LOGO_PATH)}}/" + data.setting.site_logo + "' style='width:100px;' />");
            }
            if(data.setting.footer_logo){
                $(".old-footer-logo").show();
                $('#update_site_settings #old-footer-logo').html("<img src='{{asset(LOGO_PATH)}}/" + data.setting.footer_logo + "' style='width:100px;' />");
            }

            if(data.setting.favicon_icon){
                $(".old-icon").show();
                $('#update_site_settings #old-icon').html("<img src='{{asset(LOGO_PATH)}}/" + data.setting.favicon_icon + "' style='width:100px;' />");
            }

            $('#update_site_settings #facebook').val(data.setting.facebook);
            $('#update_site_settings #twitter').val(data.setting.twitter);
            $('#update_site_settings #linkedin').val(data.setting.linkedin);
            $('#update_site_settings #youtube').val(data.setting.youtube);
            $('#update_site_settings #skype').val(data.setting.skype);
            $('#update_site_settings #instagram').val(data.setting.instagram);

            $('#update_mail_setting #mail_protocol').val(data.setting.mail_protocol);
            $('#update_mail_setting #mail_hostname').val(data.setting.mail_hostname);
            $('#update_mail_setting #mail_port').val(data.setting.mail_port);
            $('#update_mail_setting #mail_username').val(data.setting.mail_username);
            $('#update_mail_setting #mail_password').val(data.setting.mail_password);
            $('#update_mail_setting #mail_security').val(data.setting.mail_security);
            // tinyMCE.activeEditor.setContent(data.setting.terms_conditions);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
            $('.ajax_loading').hide();
        }
    });
}


</script>

@endsection

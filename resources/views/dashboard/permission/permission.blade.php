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
                                    <span>
                                        <i class="fas {{$page_icon}}"></i> {{$page_title}}
                                    </span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <!-- BEGIN:: USER PERMISSION FORM SECTION -->
                        <form method="POST"  id="permission_form" class="m-form m-form--fit m-form--label-align-right" >
                            {{ csrf_field() }}
                            <div class="form-group m-form__group row">
                                <div class="col-12">
                                    <select class="form-control chosen-select" name="role_id" id="role_id">
                                        <option value="">Please Select Role</option>
                                        @foreach ($role_list as $role)
                                        <option value="{{$role->id}}">{{$role->role_name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="error_role"></span>
                                </div>
                            </div>

                            <div class="form-group m-form__group row" id="permission" style="padding-left: 45px;">

                            </div>

                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    <div class="row">
                                        <div class="col-md-12" id="btn-section">
                                            <button type="button" class="btn btn-primary" onclick="save()">
                                                <i class="fas fa-save"></i> Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END:: USER PERMISSION FORM SECTION -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END:: MAIN CONTENT SECTION -->
</div>

<!-- BEGIN:: JS SCRIPT SECTION -->
<script>
$("#btn-section").hide();
$(document).ready(function() {
    /* BEGIN:: ON CHANGE ROLE FTECH ROLE PERMISSION DATA AJAX CODE */
    $("#role_id").change(function(){
        var role_id = $(this).val();
        if(role_id){
            $("#error_role").text('');
            $.ajax({
                url: "{{url('/permission-list')}}/"+role_id,
                type: "GET",
                dataType: "JSON",
                beforeSend: function ()
                {
                $('.ajax_loading').show();
                },
                success: function (data)
                {
                    if(data.permission){
                        // console.log(data.permission);
                        
                        $('#permission_form #permission').html(data.permission);
                        $("#btn-section").show();
                        $('.ajax_loading').hide();
                    }else{
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
                        toastr.error(data.error,"ERROR");
                        $('.ajax_loading').hide();
                    }
                
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                    $('.ajax_loading').hide();
                }
            });
        }else{
            $("#error_role").text('Please select a role');
            $('#permission_form #permission').html('');
            $("#btn-section").hide();
        }
        
    });
    /* END:: ON CHANGE ROLE FTECH ROLE PERMISSION DATA AJAX CODE */
});

/* BEGIN:: ON CHECKED PARENT CHILD ALSO CHECKED CODE */
function permissionChecked(id){
    if($('#menu_id'+id+':checked').length == 1){
        $('.menu_id_'+id).prop('checked',$(this).prop('checked',true)); 
    }else{
        $('.menu_id_'+id).prop('checked',false);
    }
}
/* END:: ON CHECKED PARENT CHILD ALSO CHECKED CODE */

/* BEGIN:: PERMISSION SAVE AJAX FUNCTION*/
function save(){
    $.ajax({
        url: "{{url('/permission-add')}}",
        type: "POST",
        data: $('#permission_form').serialize(),
        dataType: "JSON",
        beforeSend: function ()
        {
        $('.ajax_loading').show();
        },
        success: function (data)
        {
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
            if (data.success) {

                toastr.success(data.success,"SUCCESS");
                $('.ajax_loading').hide();
                location.reload();
                $("#permission_form #role_id").val('').trigger("chosen:updated");

            }else{

                toastr.error(data.error,"ERROR");
                $('.ajax_loading').hide();
            }
        },
        error: function (data)
        {
            alert('Error adding / update data');
        }
    });
}
/* END:: PERMISSION SAVE AJAX FUNCTION*/
</script>
<!-- END:: JS SCRIPT SECTION -->

@endsection


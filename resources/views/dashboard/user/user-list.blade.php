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
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                    @if(in_array('User Add',session()->get('permission')))
                                    <a href="{{url('/register')}}" class="btn add-btn" id="add_menu">
                                        <i class="fas fa-plus-square"></i>
                                        Add New
                                    </a>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <!-- BEGIN:: DATATABLE SEARCH DATA FORM SECTION -->
                        <form method="POST" id="form-filter" class="m-form m-form--fit m--margin-bottom-20" role="form">
                            {{ csrf_field() }}
                            <div class="row m--margin-bottom-20">
                                <div class="col-lg-3 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Name:</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" />
                                </div>
                                <div class="col-lg-3 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Email:</label>
                                    <input type="text" class="form-control" name="email" id="email" placeholder="Enter email" />
                                </div>
                                <div class="col-lg-3 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Mobile No.:</label>
                                    <input type="text" class="form-control" name="mobile_no" id="mobile_no" placeholder="Enter mobile no" />
                                </div>
                                <div class="col-lg-3 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Role:</label>
                                    {{-- <input type="text" class="form-control" name="role_id" id="role_id" placeholder="Enter mobile no" /> --}}
                                    <select class="form-control chosen-select" name="role_id" id="role_id">
                                        <option value="">Select Please</option>
                                        @if(count($roles) > 0)
                                            @foreach ($roles as $role)
                                                <option value="{{$role->id}}">{{$role->role_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-3 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Gender:</label>
                                    <select class="form-control chosen-select" name="gender" id="gender">
                                        <option value="">Select Please</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                    </select>
                                </div>

                                @if(in_array('User Edit',session()->get('permission')))
                                <div class="col-lg-3 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Status:</label>
                                    <select class="form-control chosen-select" name="is_active" id="is_active">
                                        <option value="">Select Please</option>
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                    </select>
                                </div>
                                @endif

                            <div class="<?php if(in_array('User Edit',session()->get('permission'))){ ?>col-lg-6<?php }else{ ?> col-lg-9 <?php } ?> col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label></label>
                                    <div style="margin-top: 7px;">
                                        
                                        <button id="btn-reset" class="btn btn-danger  dim reset-btn pull-right" data-toggle="tooltip"
                                            data-placement="top" title="" data-original-title="Reset" type="reset"><i
                                                class="fas fa-refresh"></i></button>
                                                
                                        <button id="btn-filter" class="btn dim search-btn pull-right" data-toggle="tooltip"
                                            data-placement="top" title="" data-original-title="Search" type="button"><i
                                                class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END:: DATATABLE SEARCH DATA FORM SECTION -->

                        <!-- BEGIN:: MENU TABLE SECTION -->
                        <div class="table-responsive">
                            <table class="table table-striped- table-bordered table-hover table-checkable" id="userTable">
                                <thead>
                                    <tr>
                                        <th>SR</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile No</th>
                                        <th>Gender</th>
                                        <th>Role</th>
                                        @if(in_array('User Edit',session()->get('permission')))
                                        <th>Status</th>
                                        @endif
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- END:: MENU TABLE SECTION -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END:: MAIN CONTENT SECTION -->

    <!-- BEGIN:: USER EDIT MODAL FORM -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form method="POST" id="user_form" enctype="multipart/form-data">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="user_id" />
                        <div class="form-group">
                            <label for="name" class="form-control-label">
                                Name:
                            </label>
                            <input type="text" name="name" class="form-control" id="name">
                            <div class="error error_name text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-control-label">
                                Email:
                            </label>
                            <input type="text" class="form-control" id="email" readonly>
                        </div>
                        <div class="form-group">
                            <label for="mobile_no" class="form-control-label">
                                Mobile No.:
                            </label>
                            <input type="text" name="mobile_no" class="form-control" id="mobile_no">
                            <div class="error error_mobile_no text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label for="additional_mobile_no" class="form-control-label">
                                Additional Mobile No.:
                            </label>
                            <input type="text" name="additional_mobile_no" class="form-control" id="additional_mobile_no">
                        </div>
                        <div class="form-group">
                            <label for="gender" class="form-control-label">
                                Gender:
                            </label>
                            <select class="form-control chosen-select" name="gender" id="gender">
                                <option value="">Select Please</option>
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select>
                            <div class="error error_gender text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label for="role_id" class="form-control-label">
                                Role:
                            </label>
                            <select class="form-control chosen-select" name="role_id" id="role_id">
                                <option value="">Select Please</option>
                                @if(count($roles) > 0)
                                    @foreach ($roles as $role)
                                        <option value="{{$role->id}}">{{$role->role_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="error error_role_id text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="form-control-label">
                                Address:
                            </label>
                            <textarea name="address" class="form-control" id="address"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="address" class="form-control-label">
                                Photo:
                            </label>
                            <input type="file" name="photo" class="form-control" id="photo"/>
                        </div>
                        <div class="form-group old-image">
                            <label for="old-image" class="form-control-label"></label>
                            <div class="form-control" id="old-image"></div>
                        </div>  
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-close" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="save-btn">
                            
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END:: USER EDIT MODAL FORM -->

    <!-- BEGIN:: USER PASSWORD CHANGE MODAL FORM -->
    <div class="modal fade" id="passwordChangeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form method="POST" id="password_change_form">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="change_id" />
                        <div class="form-group">
                            <label for="password" class="form-control-label">
                                Password:
                            </label>
                            <input  type="password" name="password" class="form-control" id="password">
                            <div class="error error_password text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="form-control-label">
                                Confirm Password:
                            </label>
                            <input  type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-close" data-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary" id="change-password-btn">
                            Update
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END:: USER PASSWORD CHANGE MODAL FORM -->

    <!-- BEGIN: UPLOAD & CROP PFOFILE PHOTO MODAL FORM -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"><i class="fas fa-eye"></i> View User Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body" id="user_data">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-close" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>    
    <!-- END: UPLOAD & CROP PFOFILE PHOTO MODAL FORM -->
</div>


<!-- BEGIN:: JS SCRIPT SECTION -->

<script>
var table;
$(document).ready(function () {
    $(".old-image").hide();
    /** BEGIN:: DATATABLE SERVER SIDE CODE **/
    var table = $('#userTable').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "dom": 'lTgBfrtip',
        "buttons": [
            'colvis',
            {
                "extend": 'csv',
                "title": 'User List',
                "exportOptions": {
                    "columns": [0, 2, 3, 4, 5, 6]
                    
                }
            },
            {
                "extend": 'excel',
                "title": 'User List',
                "exportOptions": {
                    "columns": [0, 2, 3, 4, 5, 6]
                }
            },
            {
                "extend": 'pdf',
                "title": 'User List',
                "exportOptions": {
                    "columns": [0, 2, 3, 4, 5, 6]
                }
            },

            {
                "extend": 'print',
                "title": 'User List',
                "exportOptions": {
                    "columns": [0, 2, 3, 4, 5, 6]

                },
                customize: function (win) {
                    //$(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ],
        "responsive": false,
        "bInfo": true,
        "bFilter": false,
        "lengthMenu": [
            [5, 10, 15, 25, 50, 100, -1],
            [5, 10, 15, 25, 50, 100, "All"]
        ],
        "pageLength": 25,
        "language": {
            processing: '<img class="loading-image" src="<?php echo asset("public/dashboard-assets/img/loading.svg"); ?>" />',
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '<strong class="text-danger">No Data Found</strong>',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>',
        },

        // Load data for the table's content from an Ajax source//
        "ajax": {
            "url": "{{url('/user-list')}}",
            "type": "POST",
            "data": function (data) {
                data.name      = $('#name').val();
                data.email     = $('#email').val();
                data.mobile_no = $('#mobile_no').val();
                data.role_id   = $('#role_id').val();
                data.gender    = $('#gender').val();
                data.is_active = $('#is_active').val();
                data._token    = "{{csrf_token()}}";
            }
        },

        "fnDrawCallback": function () {
            
            //BEGIN:: USER STATUS CHANGE AJAX CODE
            $(".change_status").on("change", function() {
                if ($(this).is(":checked")) {
                    status = 1;
                    id = $(this).data("id");
                } else {
                    status = 2;
                    id = $(this).data("id");
                }
                var _token = "{{csrf_token()}}";
                if(status && id){
                    $.ajax({
                        url: "{{url('/change-user-status')}}",
                        type: "POST",
                        data: {id:id,status:status,_token:_token},
                        dataType: "JSON",
                        beforeSend: function () {
                            $('.ajax_loading').show();
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
                                $('.ajax_loading').hide();
                                table.ajax.reload();
                                
                            } else {
                                toastr.error(data.error, "ERROR");
                                $('.ajax_loading').hide();
                                
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert('Error in change status data');
                        }
                    });
                }
            });
            //END:: USER STATUS CHANGE AJAX CODE

            //BEGIN: ON CLICK EDIT USER BUTTON FETCH USER DATA AND SHOW IT IN EDIT MODAL FORM CODE
            $('.edit_user').click(function () {
                var id = $(this).data('id');

                $('#user_form')[0].reset(); // reset form on modals
                $(".error").each(function () {
                    $(this).empty();
                });

                //Ajax Load data from ajax
                $.ajax({
                    url: "{{url('/user/edit')}}/"+id,
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) {

                        $('#user_form #user_id').val(data.user.id);
                        $('#user_form #name').val(data.user.name);
                        $('#user_form #email').val(data.user.email);
                        $('#user_form #mobile_no').val(data.user.mobile_no);
                        $('#user_form #additional_mobile_no').val(data.user.additional_mobile_no);
                        $('#user_form #role_id').val(data.user.role_id);
                        $('#user_form #role_id').trigger("chosen:updated");
                        $('#user_form #gender').val(data.user.gender);
                        $('#user_form #gender').trigger("chosen:updated");
                        $('#user_form #address').val(data.user.address);
                        if(data.user.photo){
                            $(".old-image").show();
                             $('#user_form #old-image').html("<img src='{{asset(USER_PROFILE_PHOTO)}}/" + data.user.photo + "' style='max-width:100px;' />");
                        }
                        
                        $('#userModal').modal('show');
                        $('.modal-title').html('<i class="fas fa-edit"></i> <span>Edit User</span>');
                        $('#save-btn').text('Update');

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            });
            //END: ON CLICK EDIT USER BUTTON FETCH USER DATA AND SHOW IT IN EDIT MODAL FORM CODE
           
            //BEGIN: ON CLICK VIEW USER BUTTON FETCH USER DATA AND SHOW IT IN VIEW MODAL CODE
            $('.view_user').click(function () {
                var id = $(this).data('id');

                //Ajax Load data from ajax
                $.ajax({
                    url: "{{url('/user/view')}}/"+id,
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) {

                        $('#user_data').html(data.user);
                        $('#viewModal').modal('show');

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            });
            //END: ON CLICK VIEW USER BUTTON FETCH USER DATA AND SHOW IT IN VIEW MODAL CODE

            //BEGIN: DELETE USER DATA AJAX CODE
            $('.delete_user').on('click', function(){
                var id = $(this).data('id');
                swal({
                    title: 'Are you sure?',
                    text: "It will be deleted permanently!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'btn btn-success',
                    cancelButtonColor: 'btn btn-danger',
                    confirmButtonText: 'Yes, delete it!',
                    showLoaderOnConfirm: true,
                        
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            
                            $.ajax({
                            url: '{{ url("/user/delete") }}/'+id,
                            type: 'GET',
                            dataType: 'json'
                            })
                            .done(function(response){
                                if(response.status == 'success'){
                                    swal({title: "Deleted!", text: response.message,type: "success"}
                                    ).then(function(){ 
                                        table.ajax.reload();
                                        }
                                    );

                                }else if(response.status == 'error'){
                                    swal('Error deleting!', response.message, 'error');
                                }

                            })
                            .fail(function(){
                                swal('Oops...', 'Something went wrong with ajax !', 'error');
                            });
                        });
                    },
                    allowOutsideClick: false			  
                });	
            });
            //END: DELETE USER DATA AJAX CODE

            //BEGIN: ON CLICK CHANGE PASSWORD BUTTON MODAL SHOW CODE
            $('.change_password').click(function () {
                var id = $(this).data('id');
                $('#password_change_form')[0].reset();

                $(".error").each(function () {
                    $(this).empty();
                });

                $('#passwordChangeModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                })

                $("#change_id").val(id);
                $('.modal-title').html('<i class="fas fa-refresh"></i> <span>Change Password</span>');
            });
            //END: ON CLICK CHANGE PASSWORD BUTTON MODAL SHOW CODE
            
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                <?php if(in_array('User Edit',session()->get('permission'))){ ?>
                "targets": [7,8], //first column / numbering column
                <?php }else{ ?>
                "targets": [7],
                <?php } ?>
                "orderable": false, //set not orderable
            }, 
            {
                <?php if(in_array('User Edit',session()->get('permission'))){ ?>
                "targets": [7,8],
                <?php }else{ ?>
                "targets": 7,
                <?php } ?>
                "className": "text-center",
            }
        ],
    });
    /** END:: DATATABLE SERVER SIDE CODE **/

    /** BEGIN:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/
    $("#form-filter input,#form-filter select").keyup(function (e) {
        if (e.which == 13) {
            $('button[name="search"]').trigger('click');
        }
    });

    $('#btn-filter').click(function () {
        table.ajax.reload();
    });

    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        $('#role_id').trigger("chosen:updated");
        $('#gender').trigger("chosen:updated");
        $('#is_active').trigger("chosen:updated");
        table.ajax.reload();
    });
    /** END:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/
    
    /** BEGIN:: USER DATA EDIT AJAX CODE **/
    $('#user_form').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:"{{url('/user/update')}}",
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

                    $('#userModal').modal('hide');
                    $('.ajax_loading').hide();
                    table.ajax.reload();
                } else
                {
                
                    $("#userModal").find('.error').text(''); 
                    console.log(data.errors);
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
            }
        });
    });
    /** END:: USER DATA EDIT AJAX CODE **/

     /** BEGIN:: USER PASSWORD CHANGE AJAX CODE **/
    $('#change-password-btn').click(function () {

        $.ajax({
            url: "{{url('/change-user-password')}}",
            type: "POST",
            data: $('#password_change_form').serialize(),
            dataType: "JSON",
            beforeSend: function () {
                $('.ajax_loading').show();
            },
            success: function (data) {
                if (data.success) {
                    console.log(data);
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
                    $('#passwordChangeModal').modal('hide');
                    $('.ajax_loading').hide();
                    table.ajax.reload();
                } else {

                    $("#passwordChangeModal").find('.error').text('');
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('.form-group').find('.error_'+key).text(value); 
                    });

                    $('.ajax_loading').hide();
                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error update password data');
            }
        });
    });
    /** END:: USER PASSWORD CHANGE AJAX CODE **/
});
</script>

@endsection

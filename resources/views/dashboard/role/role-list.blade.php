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
                                    @if(in_array('Role Add',session()->get('permission')))
                                    <button type="button" class="btn add-btn" id="add_role">
                                        <i class="fas fa-plus-square"></i>
                                        Add New
                                    </button>
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
                                <div class="col-lg-10 col-sm-8 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Role Name:</label>
                                    <input type="text" class="form-control" name="role_name" id="role_name" placeholder="Enter role name" />
                                </div>

                                <div class="col-lg-2 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label></label>
                                    <div style="margin-top: 7px;">
                                        <button id="btn-reset" class="btn btn-danger  dim reset-btn pull-right" data-toggle="tooltip"
                                            data-placement="top" title="" data-original-title="Reset" type="button"><i
                                                class="fas fa-refresh"></i></button>

                                        <button id="btn-filter" class="btn dim search-btn pull-right" data-toggle="tooltip"
                                            data-placement="top" title="" data-original-title="Search" type="button"><i
                                                class="fas fa-search"></i></button>
                                        
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- END:: DATATABLE SEARCH DATA FORM SECTION -->

                        <!-- BEGIN:: ROLE TABLE SECTION -->
                        <div class="table-responsive">
                            <table class="table table-striped- table-bordered table-hover table-checkable roleTable" id="roleTable">
                                <thead>
                                    <tr>
                                        <th>SR</th>
                                        <th>Role Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

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

    <!-- BEGIN:: ROLE ADD/EDIT MODAL FORM -->
    <div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <form method="POST" id="role_form">
                    <div class="modal-body">
                       {{ csrf_field() }}
                        <input type="hidden" name="id" id="role_id" />
                        <div class="form-group">
                            <label for="role_name" class="form-control-label">
                                Role name:
                            </label>
                            <input type="text" name="role_name" class="form-control" id="role_name">
                            <div class="error error_role_name text-danger"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-close" data-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary" id="save-btn">
                            
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END:: ROLE ADD/EDIT MODAL FORM -->

</div>

<!-- BEGIN:: JS SCRIPT SECTION -->
<script>
var table;
$(document).ready(function () {

    /** BEGIN:: DATATABLE SERVER SIDE CODE **/
    var table = $('#roleTable').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "dom": 'lTgBfrtip',
        "buttons": [
            'colvis',

            {
                "extend": 'csv',
                "title": 'Role List',
                "exportOptions": {
                    "columns": [0, 1]
                }
            },
            {
                "extend": 'excel',
                "title": 'Role List',
                "exportOptions": {
                    "columns": [0, 1]
                }
            },
            {
                "extend": 'pdf',
                "title": 'Role List',
                "exportOptions": {
                    "columns": [0, 1]
                }
            },

            {
                "extend": 'print',
                "title": 'Role List',
                "exportOptions": {
                    "columns": [0, 1]
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
            "url": "{{url('/role-list')}}",
            "type": "POST",
            "data": function (data) {
                data.role_name = $('#role_name').val();
                data._token = "{{csrf_token()}}";
            }
        },

        "fnDrawCallback": function () {
            //BEGIN: ON CLICK ADD ROLE BUTTON & SHOW ADD ROLE MODAL CODE
            $('#add_role').click(function () {
                save_method = 'add';
                $('#role_form')[0].reset();
                $('#role_id').val('');
                $(".error").each(function () {
                    $(this).empty();
                });

                $('#roleModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                })
                $('.modal-title').html('<i class="fas fa-plus-square"></i> <span>Add New Role</span>');
                $('#save-btn').text('Save');
            });
            //END: ON CLICK ADD ROLE BUTTON & SHOW ADD ROLE MODAL CODE

            //BEGIN: ON CLICK EDIT ROLE BUTTON FETCH ROLE DATA AND SHOW IT IN EDIT MODAL FORM CODE
            $('.edit_role').click(function () {
                var id = $(this).data('id');
                var url = "/role/" + id + "/edit";
                save_method = 'update';
                $('#role_form')[0].reset(); // reset form on modals

                $(".error").each(function () {
                    $(this).empty();
                });

                //Ajax Load data from ajax
                $.ajax({
                    url: "{{url('')}}" + url,
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) {

                        $('#role_form #role_id').val(data.role.id);
                        $('#role_form #role_name').val(data.role.role_name);
                        $('#roleModal').modal('show');
                        $('.modal-title').html('<i class="fas fa-edit"></i> <span>Edit Role</span>');
                        $('#save-btn').text('Update');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            });
            //END: ON CLICK EDIT ROLE BUTTON FETCH ROLE DATA AND SHOW IT IN EDIT MODAL FORM CODE
            
            //BEGIN: DELETE ROLE DATA CODE
            $('.delete_role').click(function () {
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

                    preConfirm: function () {
                        return new Promise(function (resolve) {

                            $.ajax({
                                    url: '{{ url("/role/delete") }}/' +
                                        id,
                                    type: 'GET',
                                    dataType: 'json'
                                })
                                .done(function (response) {
                                    if (response.status ==
                                        'success') {
                                        swal({
                                            title: "Deleted!",
                                            text: response
                                                .message,
                                            type: "success"
                                        }).then(function () {
                                            table.ajax.reload();
                                        });
                                        // setInterval( function () {
                                        //     location.reload();
                                        // }, 3000 );
                                    } else if (response.status ==
                                        'error') {
                                        swal('Error deleting!',
                                            response.message,
                                            'error');
                                    }


                                })
                                .fail(function () {
                                    swal('Oops...',
                                        'Something went wrong with ajax !',
                                        'error');
                                });
                        });
                    },
                    allowOutsideClick: false
                });
            });
            //BEGIN: DELETE ROLE DATA CODE
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [2], //first column / numbering column
                "orderable": false, //set not orderable
            }, 
            {
                "targets": 2,
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
        table.ajax.reload();
    });
    /** END:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/

    /** BEGIN:: ROLE DATA ADD/UPDATE AJAX CODE **/
    $('#save-btn').click(function () {
        var url;
        if (save_method == 'add') {
            url = "{{url('/role')}}";
        } else {
            url = "{{url('/role/update')}}";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#role_form').serialize(),
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
                    table.ajax.reload();
                    $('#roleModal').modal('hide');
                    $('.ajax_loading').hide();
                } else {

                    $("#roleModal").find('.error').text('');
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('.form-group').find('.error_'+key).text(value); 
                    });

                    $('.ajax_loading').hide();
                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            }
        });
    });
    /** END:: ROLE DATA ADD/UPDATE AJAX CODE **/
}); 
</script>
<!-- END:: JS SCRIPT SECTION -->
@endsection

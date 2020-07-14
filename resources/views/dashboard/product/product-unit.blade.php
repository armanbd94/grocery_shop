@extends('dashboard.master')

@section('title')
{{$page_title}}
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
                            <i class="{{$page_icon}}"></i> {{$page_title}}
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
                                    <span><i class="{{$page_icon}}"></i> {{$page_title}}</span>
                                </h2>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                    @if(in_array('Unit Add',session()->get('permission')))
                                    <button type="button" class="btn add-btn" id="add_data">
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
                                <div class="<?php if(in_array('Unit Edit',session()->get('permission'))){ ?> col-lg-3 <?php }else{ ?> col-lg-4 <?php } ?> col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Unit Full Name:</label>
                                    <input type="text" class="form-control" name="full_name" id="fname" placeholder="Enter full name" />
                                </div>
                                <div class="<?php if(in_array('Unit Edit',session()->get('permission'))){ ?> col-lg-3 <?php }else{ ?> col-lg-4 <?php } ?>  col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Unit Short Name:</label>
                                    <input type="text" class="form-control" name="short_name" id="sname" placeholder="Enter full name" />
                                </div>

                                 @if(in_array('Brand Edit',session()->get('permission')))
                                <div class="col-lg-3 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Status:</label>
                                    <select class="form-control chosen-select" name="status" id="status">
                                        <option value="">Select Please</option>
                                        <option value="1">Enable</option>
                                        <option value="2">Disable</option>
                                    </select>
                                </div>
                                @endif

                                <div class="<?php if(in_array('Unit Edit',session()->get('permission'))){ ?> col-lg-3 <?php }else{ ?> col-lg-4 <?php } ?>  col-sm-4 m--margin-bottom-10-tablet-and-mobile">
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
                            <table class="table table-striped- table-bordered table-hover table-checkable unitTable" id="unitTable">
                                <thead>
                                    <tr>
                                        <th>SR</th>
                                        <th>Full Name</th>
                                        <th>Short Name</th>
                                        @if(in_array('Unit Edit',session()->get('permission')))
                                        <th>Status</th>
                                        @endif
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

    <!-- BEGIN:: ADD/EDIT MODAL FORM -->
    <div class="modal fade" id="unitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <form method="POST" id="unit_form">
                    <div class="modal-body">
                       {{ csrf_field() }}
                        <input type="hidden" name="id" id="unit_id" />
                        <div class="form-group">
                            <label for="full_name" class="form-control-label">
                                Full Name:
                            </label>
                            <input type="text" name="full_name" class="form-control" id="full_name">
                            <div class="error error_full_name text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label for="short_name" class="form-control-label">
                               Short Name:
                            </label>
                            <input type="text" name="short_name" class="form-control" id="short_name">
                            <div class="error error_short_name text-danger"></div>
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
    <!-- END:: ADD/EDIT MODAL FORM -->

</div>

<!-- BEGIN:: JS SCRIPT SECTION -->
<script>
var table;
$(document).ready(function () {

    /** BEGIN:: DATATABLE SERVER SIDE CODE **/
    var table = $('#unitTable').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "dom": 'lTgBfrtip',
        "buttons": [
            'colvis',

            {
                "extend": 'csv',
                "title": 'Unit List',
                "exportOptions": {
                    "columns": [0, 1]
                }
            },
            {
                "extend": 'excel',
                "title": 'Unit List',
                "exportOptions": {
                    "columns": [0, 1]
                }
            },
            {
                "extend": 'pdf',
                "title": 'Unit List',
                "exportOptions": {
                    "columns": [0, 1]
                }
            },

            {
                "extend": 'print',
                "title": 'Unit List',
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
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>',
        },

        // Load data for the table's content from an Ajax source//
        "ajax": {
            "url": "{{url('/product-unit-list')}}",
            "type": "POST",
            "data": function (data) {
                data.fname = $('#fname').val();
                data.sname = $('#sname').val();
                data.status   = $('#status').val();
                data._token = "{{csrf_token()}}";
            }
        },

        "fnDrawCallback": function () {
            //BEGIN: ON CLICK ADD BUTTON & SHOW ADD MODAL CODE
            $('#add_data').click(function () {
                save_method = 'add';
                $('#unit_form')[0].reset();
                $('#unit_id').val('');
                $(".error").each(function () {
                    $(this).empty();
                });

                $('#unitModal').modal({
                    keyboard: false,
                    backdrop: 'static'
                })
                $('.modal-title').html('<i class="fas fa-plus-square"></i> <span>Add New Unit</span>');
                $('#save-btn').text('Save');
            });
            //END: ON CLICK ADD BUTTON & SHOW ADD MODAL CODE

            //BEGIN: ON CLICK EDIT BUTTON FETCH DATA AND SHOW IT IN EDIT MODAL FORM CODE
            $('.edit_data').click(function () {
                var id = $(this).data('id');
                var url = "/product-unit/" + id + "/edit";
                save_method = 'update';
                $('#unit_form')[0].reset(); // reset form on modals

                $(".error").each(function () {
                    $(this).empty();
                });

                //Ajax Load data from ajax
                $.ajax({
                    url: "{{url('')}}" + url,
                    type: "GET",
                    dataType: "JSON",
                    success: function (data) {

                        $('#unit_form #unit_id').val(data.unit.id);
                        $('#unit_form #full_name').val(data.unit.full_name);
                        $('#unit_form #short_name').val(data.unit.short_name);
                        $('#unitModal').modal('show');
                        $('.modal-title').html('<i class="fas fa-edit"></i> <span>Edit Unit</span>');
                        $('#save-btn').text('Update');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            });
            //END: ON CLICK EDIT ROLE BUTTON FETCH ROLE DATA AND SHOW IT IN EDIT MODAL FORM CODE
            
            //BEGIN: DELETE ROLE DATA CODE
            $('.delete_data').click(function () {
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
                                    url: '{{ url("/product-unit/delete") }}/' +
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
                        url: "{{url('/change-product-unit-status')}}",
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
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-bottom-right",
                                "preventDuplicates": false,
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
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [3,4], //first column / numbering column
                "orderable": false, //set not orderable
            }, 
            {
                "targets": 4,
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
        $('#status').trigger("chosen:updated");
        table.ajax.reload();
    });
    /** END:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/

    /** BEGIN:: ROLE DATA ADD/UPDATE AJAX CODE **/
    $('#save-btn').click(function () {
        // ajax adding data to database
        $.ajax({
            url: "{{url('/product-unit')}}",
            type: "POST",
            data: $('#unit_form').serialize(),
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
                    $('#unitModal').modal('hide');
                    
                } else {

                    $("#unitModal").find('.error').text('');
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('.form-group').find('.error_'+key).text(value); 
                    });
                }
                $('.ajax_loading').hide();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('.ajax_loading').hide();
            }
        });
    });
    /** END:: ROLE DATA ADD/UPDATE AJAX CODE **/
}); 
</script>
<!-- END:: JS SCRIPT SECTION -->
@endsection

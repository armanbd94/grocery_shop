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
                                <!--
                                <li class="m-portlet__nav-item">
                                    @if(in_array('Menu Add',session()->get('permission')))
                                    <button type="button" class="btn add-btn" id="add_menu">
                                        <i class="fas fa-plus-square"></i>
                                        Add New
                                    </button>
                                    @endif
                                </li>
                                -->
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <!-- BEGIN:: DATATABLE SEARCH DATA FORM SECTION -->
                        <form method="POST" id="form-filter" class="m-form m-form--fit m--margin-bottom-20" role="form">
                            {{ csrf_field() }}
                            <div class="row m--margin-bottom-20">
                                <div class="col-lg-3 col-sm-3 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Customer Name:</label>
                                    <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Enter customer name" />
                                </div>
                                <div class="col-lg-3 col-sm-3 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Email:</label>
                                    <input type="text" class="form-control" name="email" id="customer_email" placeholder="Enter customer Email" />
                                </div>
                                <div class="col-lg-3 col-sm-3 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Mobile:</label>
                                    <input type="text" class="form-control" name="mobile" id="customer_mobile" placeholder="Enter customer Mobile" />
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

                        <!-- BEGIN:: MENU TABLE SECTION -->
                        <div class="table-responsive">
                            <table class="table table-striped- table-bordered table-hover table-checkable" id="customerTable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="form-control selectall"></th>                               <th>SR</th>
                                        <th>Customer Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Status</th>
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

    <!-- BEGIN: CUSTOMER VIEW MODAL FORM -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"><i class="fas fa-eye"></i> View Customer Data</h5>
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
    <!-- END: CUSTOMER VIEW MODAL FORM -->

</div>

<!-- BEGIN:: JS SCRIPT SECTION -->
<script>
var table;
$(document).ready(function() {

    /** BEGIN:: DATATABLE SERVER SIDE CODE **/
    var table = $('#customerTable').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "dom": '<"toolbar">lTgBfrtip',
        "buttons": [
            'colvis',
            {
                "extend": 'csv',
                "title": 'Customer List',
                "exportOptions": {
                    "columns": [1, 2, 3]
                }
            },
            {
                "extend": 'excel',
                "title": 'Customer List',
                "exportOptions": {
                    "columns": [1, 2, 3]
                }
            },
            {
                "extend": 'pdf',
                "title": 'Customer List',
                "exportOptions": {
                    "columns": [1, 2, 3]
                }
            },
            {
                "extend": 'print',
                "title": 'Customer List',
                "exportOptions": {
                    "columns": [1, 2, 3]
                },
                customize: function(win) {
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
            "url": "{{url('/customer-list')}}",
            "type": "POST",
            "data": function(data) {
                data.customer_name = $('#customer_name').val();
                data.email = $('#customer_email').val();
                data.mobile = $('#customer_mobile').val();
                data._token = "{{csrf_token()}}";
            }
        },

        "fnDrawCallback": function() {

            //BEGIN:: CUSTOMER  STATUS CHANGE AJAX CODE
            $(".change_status").on("change", function() {
                if ($(this).is(":checked")) {
                    status = 1;
                    id = $(this).data("id");
                } else {
                    status = 2;
                    id = $(this).data("id");
                }
                var _token = "{{csrf_token()}}";
                if (status && id) {
                    $.ajax({
                        url: "{{url('/change-customer-status')}}",
                        type: "POST",
                        data: {
                            id: id,
                            status: status,
                            _token: _token
                        },
                        dataType: "JSON",
                        beforeSend: function() {
                            $('.ajax_loading').show();
                        },
                        success: function(data) {
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
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('Error in change status data');
                        }
                    });
                }
            });
            //END:: CUSTOMER STATUS CHANGE AJAX CODE


            //BEGIN: ON CLICK VIEW USER BUTTON FETCH USER DATA AND SHOW IT IN VIEW MODAL CODE
            $('.view_customer').click(function() {
                var id = $(this).data('id');

                //Ajax Load data from ajax
                $.ajax({
                    url: "{{url('/customer/view')}}/" + id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {

                        $('#user_data').html(data.customer);
                        $('#viewModal').modal('show');

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                });
            });
            //END: ON CLICK VIEW USER BUTTON FETCH USER DATA AND SHOW IT IN VIEW MODAL CODE

            
            /*
            //BEGIN: DELETE CUSTOMER DATA CODE
            $('.delete_data').click(function() {
                var id = $(this).data('id');
                swal({
                    title: 'Are you sure?',
                    text: "It will be deleted permanently!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'btn',
                    cancelButtonColor: 'btn btn-danger',
                    confirmButtonText: 'Yes, delete it!',
                    showLoaderOnConfirm: true,

                    preConfirm: function() {
                        return new Promise(function(resolve) {

                            $.ajax({
                                    url: '{{ url("/customer/delete") }}/' + id,
                                    type: 'GET',
                                    dataType: 'json'
                                }).done(function(response) {
                                    if (response.status == 'success') {
                                        swal({
                                            title: "Deleted!",
                                            text: response
                                                .message,
                                            type: "success"
                                        }).then(function() {
                                            table.ajax.reload();
                                        });
                                    } else if (response.status == 'error') {
                                        swal('Error deleting!', response.message, 'error');
                                    }


                                })
                                .fail(function() {
                                    swal('Oops...',
                                        'Something went wrong with ajax !',
                                        'error');
                                });
                        });
                    },
                    allowOutsideClick: false
                });
            });
            //END: DELETE MENU DATA CODE

            $('.selectall').change(function() {
                if ($('.selectall:checked').length == 1) {
                    $('.delete_customer').prop('checked', $(this).prop('checked', true));
                    if ($('.delete_customer').is(':checked')) {
                        $('.delete_customer').closest('tr').addClass('removeRow');
                    } else {
                        $('.delete_customer').closest('tr').removeClass('removeRow');
                    }
                } else {
                    $('.delete_customer').prop('checked', false);
                    if ($('.delete_customer').is(':checked')) {
                        $('.delete_customer').closest('tr').addClass('removeRow');
                    } else {
                        $('.delete_customer').closest('tr').removeClass('removeRow');
                    }
                }

            });


            $('#customers_delete').click(function() {


                //Multiple Dealer data delete  code
                var id = [];
                $('.delete_customer:checked').each(function(i) {
                    id.push($(this).val());
                });
                console.log(id);

                if (id.length === 0) //tell us if the array is empty
                {
                    swal('Oops...','Please select at least one row to delete','error');
                } else {
                    swal({
                        title: 'Are you sure?',
                        text: "It will be deleted permanently!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: 'btn',
                        cancelButtonColor: 'btn btn-danger',
                        confirmButtonText: 'Yes, delete it!',
                        showLoaderOnConfirm: true,

                        preConfirm: function() {
                            return new Promise(function(resolve) {

                                $.ajax({
                                        url: '{{ url("/customers-delete") }}',
                                        type: "POST",
                                        dataType: "JSON",
                                        data: {
                                            id: id,
                                            _token: "{{csrf_token()}}"
                                        },
                                    }).done(function(response) {
                                        if (response.status == 'success') {
                                            swal({
                                                title: "Deleted!",
                                                text: response
                                                    .message,
                                                type: "success"
                                            }).then(function() {
                                                table.ajax.reload();
                                            });
                                        } else if (response.status == 'error') {
                                            swal('Error deleting!', response.message, 'error');
                                        }


                                    })
                                    .fail(function() {
                                        swal('Oops...',
                                            'Something went wrong with ajax !',
                                            'error');
                                    });
                            });
                        },
                        allowOutsideClick: false
                    });

                }

            });
            */

        },

        //Set column definition initialisation properties.
        "columnDefs": [{
                "targets": [0, 5, 6], //first column / numbering column
                "orderable": false, //set not orderable
            },
            {
                "targets": [4, 5], //TARGET ACTION COLUMN TO MAKE IT TEXT IN CENTER POSITION
                "className": "text-center",
            }
        ],
    });
    /** END:: DATATABLE SERVER SIDE CODE **/

    $("div.toolbar").html('<button class="btn btn-danger" type="button" id="customers_delete">Delete All</button>');
    /** BEGIN:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/
    $("#form-filter input,#form-filter select").keyup(function(e) {
        if (e.which == 13) {
            $('button[name="search"]').trigger('click');
        }
    });

    $('#btn-filter').click(function() {
        table.ajax.reload();
    });

    $('#btn-reset').click(function() {
        $('#form-filter')[0].reset();
        table.ajax.reload();
    });
    /** END:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/



});

</script>
<!-- END:: JS SCRIPT SECTION -->

@endsection

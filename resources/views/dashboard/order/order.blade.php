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
                        <a href="{{url('/')}}" class="m-nav__link m-nav__link--cover_image">
                            <i class="m-nav__link-cover_image la la-home"></i>
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
                    </div>
                    <div class="m-portlet__body">
                        <!-- BEGIN:: DATATABLE SEARCH DATA FORM SECTION -->
                        <form method="POST" id="form-filter" class="m-form m-form--fit m--margin-bottom-20" role="form">
                            {{ csrf_field() }}
                            <div class="row m--margin-bottom-20">
                                <div class="col-lg-3 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Order From Date:</label>
                                    <input type="text" class="form-control date" name="order_from_date" id="order_from_date" readonly placeholder="Pick a date" />
                                </div>
                                <div class="col-lg-3 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Order To Date:</label>
                                    <input type="text" class="form-control date" name="order_to_date" id="order_to_date" readonly placeholder="Pick a date" />
                                </div>
                                <div class="col-lg-3 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Invoice No:</label>
                                    <input type="text" class="form-control" name="invoice_no" id="invoice_no" placeholder="Enter invoice no" />
                                </div>
                                <div class="col-lg-3 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Customer Mobile No:</label>
                                    <input type="text" class="form-control" name="mobile_no" id="mobile_no" placeholder="Enter mobile no" />
                                </div>
                                <div class="col-lg-3 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Delivery From Date:</label>
                                    <input type="text" class="form-control date" name="delivery_from_date" id="delivery_from_date" readonly placeholder="Pick a date" />
                                </div>
                                <div class="col-lg-3 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Delivery To Date:</label>
                                    <input type="text" class="form-control date" name="delivery_to_date" id="delivery_to_date" readonly placeholder="Pick a date" />
                                </div>

                                <div class="col-lg-3 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Delivery Time:</label>
                                    <select class="form-control chosen-select" name="delivery_time" id="delivery_time">
                                        <option value="">Select Please</option>
                                        <option value="11:00AM - 11:59PM">11:00AM - 12:00PM</option>
                                        <option value="12:00PM - 12:59PM">12:00PM - 12:59PM</option>
                                        <option value="1:00PM - 1:59PM">1:00PM - 1:59PM</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Order Status:</label>
                                    <select class="form-control chosen-select" name="order_status" id="order_status">
                                        <option value="">Select Please</option>
                                        <option value="1">Confirmed</option>
                                        <option value="2">Cancel</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Delivery Status:</label>
                                    <select class="form-control chosen-select" name="delivery_status" id="delivery_status">
                                        <option value="">Select Please</option>
                                        <option value="1">Pending</option>
                                        <option value="2">Delivered</option>
                                    </select>
                                </div>

                                <div class="col-lg-9 col-sm-4 m--margin-bottom-10-tablet-and-mobile">
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
                            <table class="table table-striped- table-bordered table-hover table-checkable" id="productTable">
                                <thead>
                                    <tr>
                                        <th width="5%">SR</th>
                                        <th>Invoice No</th>
                                        <th>View Status</th>
                                        <th>Customer</th>
                                        <th>Mobile</th>
                                        <th>Total Amount</th>
                                        <th>Fee</th>
                                        <th>Order Date</th>
                                        <th>Order Status</th>
                                        <th>Delivery Date</th>
                                        <th>Delivery Time</th>
                                        <th>Delivery Status</th>
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

</div>




<script>
var table;
$(".date").datepicker({
    todayHighlight: !0,
    format: 'yyyy-mm-dd',
    orientation: "bottom left",
    templates: {
        leftArrow: '<i class="la la-angle-left"></i>',
        rightArrow: '<i class="la la-angle-right"></i>'
    }
})
$(document).ready(function () {

    /** BEGIN:: DATATABLE SERVER SIDE CODE **/
    var table = $('#productTable').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "dom": 'lTgBfrtip',
        "buttons": [
            'colvis',
            {
                "extend": 'csv',
                "title": '{{$page_title}}',
                "exportOptions": {
                    "columns": [0,1,3,4,5,6,7,8,9,10]
                    
                }
            },
            {
                "extend": 'excel',
                "title": '{{$page_title}}',
                "exportOptions": {
                   "columns": [0,1,3,4,5,6,7,8,9,10]
                }
            },
            {
                "extend": 'pdf',
                "title": '{{$page_title}}',
                "exportOptions": {
                    "columns": [0,1,3,4,5,6,7,8,9,10]
                }
            },

            {
                "extend": 'print',
                "title": '{{$page_title}}',
                "exportOptions": {
                    "columns": [0,1,3,4,5,6,7,8,9,10]
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
        "responsive": true,
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
            "url": "{{url('order-list')}}",
            "type": "POST",
            "data": function (data) {
                data.order_from_date    = $('#order_from_date').val();
                data.order_to_date      = $('#order_to_date').val();
                data.invoice_no         = $('#invoice_no').val();
                data.mobile_no          = $('#mobile_no').val();
                data.delivery_from_date = $('#delivery_from_date').val();
                data.delivery_to_date   = $('#delivery_to_date').val();
                data.delivery_time      = $('#delivery_time').val();
                data.order_status       = $('#order_status').val();
                data.delivery_status    = $('#delivery_status').val();
                data._token             = "{{csrf_token()}}";
            }
        },

        "fnDrawCallback": function () {
            
      

            //BEGIN: CHANGE ORDER STATUS DATA AJAX CODE
            $(document).on('click','.change_status', function(){
                var id = $(this).data('id');
                var status = $(this).data('status');
                var type = $(this).data('type');
                swal({
                    title: 'Are you sure?',
                    text: "Status will cahnge",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'btn btn-success',
                    cancelButtonColor: 'btn btn-danger',
                    confirmButtonText: 'Yes, delete it!',
                    showLoaderOnConfirm: true,
                        
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                url: '{{ url("change-status") }}',
                                type: 'POST',
                                data:{id:id,status:status,type:type},
                                dataType: 'json'
                            })
                            .done(function(response){
                                if(response.status == 'success'){
                                    swal({title: "Changed!", text: response.message,type: "success"}
                                    ).then(function(){ 
                                        table.ajax.reload();
                                        }
                                    );

                                }else if(response.status == 'error'){
                                    swal('Error changing!', response.message, 'error');
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

            //BEGIN: DELETE USER DATA AJAX CODE
            $(document).on('click','.delete_data', function(){
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
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                url: '{{ url("order-delete") }}',
                                type: 'POST',
                                data:{id:id},
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
            
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [2,10], //first column / numbering column
                "orderable": false, //set not orderable
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
        $('.chosen-select').trigger("chosen:updated");
        table.ajax.reload();
    });
    /** END:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/
    

});


</script>

@endsection

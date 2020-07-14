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
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                     @if(in_array('Product Add',session()->get('permission')))
                                    <a href="{{url('product-add')}}" type="button" class="btn add-btn" id="add_data">
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
                                <div class="<?php if(in_array('Product Edit',session()->get('permission'))){ ?> col-lg-3 <?php }else{ ?> col-lg-4 <?php } ?> col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Name:</label>
                                    <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Enter name" />
                                </div>
                                <div class="<?php if(in_array('Product Edit',session()->get('permission'))){ ?> col-lg-3 <?php }else{ ?> col-lg-4 <?php } ?> col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Brand:</label>
                                    <select class="form-control chosen-select" name="brand_id" id="brand_id">
                                        <option value="">Select Please</option>
                                        @if (!empty($brands))
                                            @foreach($brands as $brand)
                                                <option value="{{$brand->id}}">{{$brand->brand_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>



                                @if(in_array('Product Edit',session()->get('permission')))
                                <div class="col-lg-3 col-sm-12 m--margin-bottom-10-tablet-and-mobile">
                                    <label>Status:</label>
                                    <select class="form-control chosen-select" name="status" id="status">
                                        <option value="">Select Please</option>
                                        <option value="1">Enable</option>
                                        <option value="2">Disable</option>
                                    </select>
                                </div>
                                @endif

                                <div class="<?php if(in_array('Product Edit',session()->get('permission'))){ ?>col-lg-3<?php }else{ ?> col-lg-4 <?php } ?> col-sm-4 m--margin-bottom-10-tablet-and-mobile">
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
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Brand</th>
                                        <th>Weight</th>
                                        <th>Price</th>
                                        @if(in_array('Product Edit',session()->get('permission')))
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

</div>




<script>
var table;

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
                "title": 'Product List',
                "exportOptions": {
                    "columns": [0,1,2,3,4,5]
                    
                }
            },
            {
                "extend": 'excel',
                "title": 'Product List',
                "exportOptions": {
                   "columns": [0,1,2,3,4,5]
                }
            },
            {
                "extend": 'pdf',
                "title": 'Product List',
                "exportOptions": {
                    "columns": [0,1,2,3,4,5]
                }
            },

            {
                "extend": 'print',
                "title": 'Product List',
                "exportOptions": {
                    "columns": [0,1,2,3,4,5]
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
            "url": "{{url('/product-list')}}",
            "type": "POST",
            "data": function (data) {
                data.product_name = $('#product_name').val();
                data.brand_id     = $('#brand_id').val();
                data.status       = $('#status').val();
                data._token       = "{{csrf_token()}}";
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
                        url: "{{url('/change-product-status')}}",
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
                                "batch_noClass": "toast-top-right",
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
                                table.ajax.reload();
                                
                            } else {
                                toastr.error(data.error, "ERROR");
                                
                            }
                            $('.ajax_loading').hide();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert('Error in change status data');
                            $('.ajax_loading').hide();
                        }
                    });
                }
            });
            //END:: USER STATUS CHANGE AJAX CODE
      

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
                                url: '{{ url("product/delete") }}',
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
                <?php if( in_array('Product Edit',session()->get('permission'))){ ?>
                "targets": [1,4,5,6,7], //first column / numbering column
                <?php }else{ ?>
                "targets": [1,4,5,6],
                <?php } ?>
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
        $('#brand_id').trigger("chosen:updated");
        $('#status').trigger("chosen:updated");
        table.ajax.reload();
    });
    /** END:: DATATABLE SEARCH FORM BUTTON TRIGGER CODE **/
    

});


</script>

@endsection

@extends('dashboard.master')

@section('title')
{{$page_title}}
@endsection

@section('main_content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
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
    <!-- END: Subheader -->    

    <!-- BEGIN: Main Content Section -->
    <div class="m-content">
        <div class="row">
            <div class="col-xl-12">
                <div class="m-portlet m-portlet--creative m-portlet--bordered-semi">
                    <div class="m-portlet__head" style="padding-top:50px;">
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
                                   <button type="button" id="printButton" class="btn btn-info m-btn m-btn--air m-btn--custom back-btn">
                                    <i class="fas fa-print"></i> Print
                                    </button>
                                </li>
                                <li class="m-portlet__nav-item">
                                   <a href="{{url('order')}}" class="btn btn-warning m-btn m-btn--air m-btn--custom back-btn">
                                    <i class="fas fa-reply-all"></i> Back
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-content">
                    
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="m-portlet" id="invoice_wrapper">
                                    <link href="{{asset('public/dashboard-assets/print.css')}}" rel="stylesheet" type="text/css" />
                                        <div class="m-portlet__body m-portlet__body--no-padding">
                                            <div class="m-invoice-2">
                                                <div class="m-invoice__wrapper" style="padding-top:100px;">
                                                    <div class="m-invoice__head">
                                                        <div class="m-invoice__container m-invoice__container--centered">
                                                            <div class="m-invoice__logo">
                                                                
                                                                <a href="#">
                                                                    <img src="{{asset(LOGO_PATH.Helper::get_site_data()->footer_logo)}}" alt="Logo" style="width:200px;">
                                                                </a>
                                                                <a href="#">
                                                                    <h1>
                                                                        INVOICE
                                                                    </h1>
                                                                </a>
                                                            </div>
                                                            <span class="m-invoice__desc text-left">
                                                                <span>{{Helper::get_site_data()->contact_no}}</span>
                                                                <span>{{Helper::get_site_data()->contact_email}}</span>
                                                                <span>{{Helper::get_site_data()->contact_address}}</span>
                                                            </span>
                                                            <div class="m-invoice__items">
                                                                <div class="m-invoice__item" style="padding-right:20px;">
                                                                    <span class="m-invoice__subtitle">
                                                                        BILLING INFORMATION
                                                                    </span>
                                                                    <span class="m-invoice__text">{{$order_data->bfname.' '.$order_data->blname}}</span>
                                                                    <span class="m-invoice__text">974{{$order_data->bmobile}}</span>
                                                                    <span class="m-invoice__text">{{$order_data->baddress}}</span>
                                                                    <span class="m-invoice__text">{{$order_data->bdistrict.', '.$order_data->bcity.', Qatar'}}</span>
                                                                </div>
                                                                <div class="m-invoice__item" style="padding-right:20px;">
                                                                    <span class="m-invoice__subtitle">
                                                                        SHIPPING INFORMATION
                                                                    </span>
                                                                    <span class="m-invoice__text">{{$order_data->sfname.' '.$order_data->slname}}</span>
                                                                    <span class="m-invoice__text">974{{$order_data->smobile}}</span>
                                                                    <span class="m-invoice__text">{{$order_data->saddress}}</span>
                                                                    <span class="m-invoice__text">{{$order_data->sdistrict.', '.$order_data->scity.', Qatar'}}</span>
                                                                </div>
                                                                <div class="m-invoice__item text-right">
                                                                    <span class="m-invoice__subtitle text-left">
                                                                        INVOICE NO. #{{$order_data->invoice_no}}
                                                                    </span>
                                                                    <span class="m-invoice__text one"><b>Order Date</b>{{date('d-M-Y',strtotime($order_data->created_at))}}</span>
                                                                    <span class="m-invoice__text two"><b>Delivery Date</b>{{date('d-M-Y',strtotime($order_data->delivery_date))}}</span>
                                                                    <span class="m-invoice__text three"><b>Delivery Time</b>{{$order_data->delivery_time}}</span>
                                                                    <span class="m-invoice__text no_print four" id="delivery_status"><b>Delivery Status</b>
                                                                        <span class="m-badge m-badge--wide {{json_decode(STATUS_TYPE)[$order_data->delivery_status]}} font-weight-bold">{{json_decode(DELIVERY_STATUS)[$order_data->delivery_status]}}</span>
                                                                    </span>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="m-invoice__body m-invoice__body--centered">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered" id="invoice_table">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="5%" class="text-center">#</th>
                                                                        <th width="50%" class="text-left"> PRODUCT</th>
                                                                        <th width="15%" class="text-right"> PRICE</th>
                                                                        <th width="10%" class="text-center"> QTY</th>
                                                                        <th width="20%" class="text-right"> SUBTOTAL</th>
                                                                        <th width="20%" class="text-right no_print "> ACTION</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    {!! $product_list !!}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN: Main Content Section -->

    <!-- BEGIN:: ADD AND EDIT MODAL FORM -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form method="POST" id="editForm">
                    <div class="modal-body">
                        <div class="form-group m-form__group row">
                            @csrf
                            <input type="hidden" name="id" id="product_id" />
                            <div class="col-lg-12 pb-15">
                                <label for="product_name" class="form-control-label">
                                   Name:
                                </label>
                                <input type="text" class="form-control" id="product_name" readonly>
                            </div>
                            <div class="col-lg-12 pb-15">
                                <label for="price" class="form-control-label">
                                    Price:
                                </label>
                                <input type="text" name="price" class="form-control" id="price" readonly>
                            </div>
                            <div class="col-lg-12 pb-15">
                                <label for="quantity" class="form-control-label">
                                    Quantity:
                                </label>
                                <input type="text" name="quantity" class="form-control" id="quantity" readonly>
                                <span class="error error_quantity text-danger"></span>
                            </div> 
                            <div class="col-lg-12 pb-15">
                                <label for="quantity" class="form-control-label">
                                    Total Price:
                                </label>
                                <input type="text" class="form-control" id="total" readonly>
                            </div> 
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
    <!-- END:: ADD AND EDIT MODAL FORM -->

</div>

@endsection

@section('script')
<script src="{{asset('public/dashboard-assets/demo/default/custom/crud/forms/widgets/bootstrap-touchspin.js')}}"></script>
<script src="{{asset('public/dashboard-assets/vendors/custom/printarea/jquery.printarea.js')}}" type="text/javascript"></script>
<script>
$(document).ready(function(){

    $("#printButton").click(function () {
        $('#delivery_status').hide();
	    var mode = 'iframe'; // popup
	    var close = mode == "popup";
	    var options = {mode: mode, popClose: close};
	    $("#invoice_wrapper").printArea(options);
	});
    $('#quantity').TouchSpin({
            buttondown_class: "btn btn-secondary",
            buttonup_class: "btn btn-secondary",
            min: 1,
            max: 100,
            step: 1,
    });

    $('#editForm').on('submit', function(event){
        event.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url:"{{url('update-ordered-product-qty')}}",
            method:"POST",
            data:$(this).serialize(),
            beforeSend: function () {
               $('.ajax_loading').show();
            },
            complete: function() {
               $('.ajax_loading').hide();
            },
            success:function(data)
            {
                if(data.success){
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
                    $('#editModal').modal('hide');
                    toastr.success(data.success, "SUCCESS");
                    get_ordered_product_list();
                }else if(data.error){
                    toastr.error(data.error, "ERROR");
                }
                
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error update data');
            }
        })
        
    });

    $(document).on('click','.edit_product', function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        var price = $(this).data('price');
        var qty = $(this).data('qty');
        var total = $(this).data('total');
        $(".error").each(function () {
            $(this).empty();
        });
        $('#product_id').val(id)
        $('#product_name').val(name)
        $('#price').val(price)
        $('#quantity').val(qty)
        $('#total').val(total)
        $('#editModal').modal({
            keyboard: false,
            backdrop: 'static'
        })


        $('.modal-title').html('<i class="fas fa-plus-square"></i> <span>Edit</span>');
        $('#save-btn').text('Update');
    });


    $(document).on('click','.bootstrap-touchspin-up, .bootstrap-touchspin-down', function(){

        var price =  $('#price').val()
        var qty = $('#quantity').val();
        var subtotal = qty * price;
        var total = parseFloat(subtotal).toFixed(3);
        $('#total').val(total);

    });

    $(document).on('click','.change_availibilty_status', function(){
                var id = $(this).data('id');
                var status = $(this).data('status');
                swal({
                    title: 'Are you sure?',
                    text: "Status will cahnge",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'btn btn-success',
                    cancelButtonColor: 'btn btn-danger',
                    confirmButtonText: 'Yes',
                    showLoaderOnConfirm: true,
                        
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                url: '{{ url("change-product-availibilty-status") }}',
                                type: 'POST',
                                data:{id:id,status:status},
                                dataType: 'json'
                            })
                            .done(function(response){
                                if(response.status == 'success'){
                                    swal({title: "Changed!", text: response.message,type: "success"}
                                    ).then(function(){ 
                                        get_ordered_product_list();
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


});
function get_ordered_product_list(){
    var order_id = '{{$order_data->id}}';
    var code     = '{{$order_data->coupon_code}}';
    var discount = '{{$order_data->coupon_discount}}';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url:"{{url('get-ordered-product-list')}}",
        method:"POST",
        data:{order_id:order_id,code:code,discount:discount},
        success:function(data)
        {
            $('#invoice_table tbody').html('');
            $('#invoice_table tbody').html(data);
        }
    })
}


</script>
@endsection
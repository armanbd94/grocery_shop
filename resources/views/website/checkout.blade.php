@extends('website.master')

@section('title')
{{ucwords($page_title)}}
@endsection
@section('stylesheet')
<link rel="stylesheet" href="{{asset('public/website-assets/css/bootstrap-datepicker.min.css')}}" />
@endsection

@section('main_content')
<section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{url('/')}}"><strong><span class="mdi mdi-home"></span> Home</strong></a>
                <span class="mdi mdi-chevron-right"></span><a href="{{url('cart')}}">Shopping Cart</a>
                <span class="mdi mdi-chevron-right"></span><a>Checkout</a>
            </div>
        </div>
    </div>
</section>
<section class="checkout-page section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12 py-20px" id="checkout-content">

                <div id="accordion_section" class="col-md-12">
                    <div class="panel_header col-md-12 py-10px" id="checkout_option_header"><h5>Checkout Option</h5><i class='fa'></i></div>
                    <div class="panel_body col-md-12" id="checkout_option_body">
                        @if (!Auth::guard('customer')->check())
                        <div class="row">
                            <div class="col-sm-6">
                                <form method="post" id="user_checkout_type_form">
                                    @csrf
                                    <h2>New Customer</h2>
                                    <p>Checkout Options:</p>
                                    <div class="radio">
                                        <label> <input type="radio" class="user_account" name="user_account" value="register" checked="checked"> Register Account</label>
                                    </div>
                                    <p>By creating an account you will be able to shop faster, be up to date on an order's status, and keep track of the orders you have previously made.</p>
                                    <button type="button" id="button-account" class="btn btn-secondary" onclick="user_checkout_type()">Continue</button>
                                </form>
                            </div>
                            <div class="col-sm-6">
                                <h2>Returning Customer</h2>
                                <p>I am a returning customer</p>
                                <form  action="{{url('/account-login')}}" method="post">
                                    @csrf
                                    <div class="form-group required" style="display:  none ;">
                                        <label class="control-label">Customer Group</label>
                                        <div class="">                            
                                            <div class="radio">
                                                <label>
                                                <input type="radio" name="redirect" value="2" checked="checked">
                                                Redirect</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="control-label" for="email">Email</label>
                                        <input type="text" name="email" value="" placeholder="E-Mail" id="email" class="form-control">
                                        @if ($errors->has('email'))
                                            <span class="text-danger" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group required">
                                        <label class="control-label" for="input-password">Password</label>
                                        <input type="password" name="password" value="" placeholder="Password"  id="password" class="form-control">
                                            @if ($errors->has('password'))
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        <div class="forget-password"><a  href="{{url('account/forgot-password')}}">Forgotten Password</a></div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-secondary">Login</button>
                                    </div>
                                    <div class="or-seperator"><i>or</i></div>
                                    <div class="form-group d-flex justify-comtemt-space-betwwen">
                                            <a href="{{url('/customer-login','google')}}" class="col-md-6 p-2px">
                                                <button type="button" class="btn btn-google col-md-12" ><i class="fa fa-google"></i> Google </button> 
                                            </a>
                                            <a href="{{url('/customer-login','facebook')}}" class="col-md-6 p-2px">
                                                <button type="button" class="btn btn-facebook col-md-12"><i class="fa fa-facebook"></i> Facebook </button>
                                             </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="panel_header col-md-12 py-10px" id="billing_option_header"><h5>Billing Details</h5><i class='fa'></i></div>
                    <div class="panel_body col-md-12" id="billing_option_body"></div>

                    <div class="panel_header col-md-12 py-10px" id="delivery_option_header"><h5>Delivery Details</h5><i class='fa'></i></div>
                    <div class="panel_body col-md-12" id="delivery_option_body"></div>

                    <div class="panel_header col-md-12 py-10px" id="delivery_slot_header"><h5>Delivery Slot</h5><i class='fa'></i></div>
                    <div class="panel_body col-md-12" id="delivery_slot_body"></div>

                    <div class="panel_header col-md-12 py-10px" id="payment_option_header"><h5>Payment Method</h5><i class='fa'></i></div>
                    <div class="panel_body col-md-12" id="payment_option_body"></div>

                    <div class="panel_header col-md-12 py-10px" id="confirm_option_header"><h5>Confirm Order</h5><i class='fa'></i></div>
                    <div class="panel_body col-md-12" id="confirm_option_body"></div>
                </div>
                
            </div>
            
        </div>
    </div>
</section>
@endsection

@section('script')
<script src="{{asset('public/website-assets/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('public/website-assets/js/moment.js')}}"></script>
<link href="{{asset('public/website-assets/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('public/website-assets/plugins/sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<script>
$('.user_account').click(function(){
    if($(this).val() == 'register'){
        $('#billing_option_header h5').text('Account & Billing Details');
    }else if($(this).val() == 'guest'){
        $('#billing_option_header h5').text('Billing Details');
    }
});

<?php
if (!Auth::guard('customer')->check()){
    ?>
$("#checkout_option_header").children(".fa").show().addClass('fa-minus-circle');
$("#checkout_option_body").show();
// get_checkout_option_body();
<?php
}
?>


// function get_checkout_option_body() {
//     var _token = "{{csrf_token()}}";
//     var url = '{{url("checkout-option-body")}}';
//     $.ajax({
//         url: url,
//         type: "POST",
//         data: {_token:_token },
//         success: function (data) {
//             if ($('#accordion_section .panel_body').is(':visible')) {
//                 $("#accordion_section .panel_body").slideUp('slow');
//                 $("#accordion_section .panel_header").children(".fa").removeClass('fa-minus-circle');
//             }
//             $("#checkout_option_header").children(".fa").show().addClass('fa-plus-circle');
//             // $("#checkout_option_header").children(".fa").addClass('fa-plus-circle');
//             $("#checkout_option_body").slideDown('slow');
//             $('#checkout_option_body').html('');
//             $('#checkout_option_body').html(data);
//         },
//         error: function (jqXHR, textStatus, errorThrown) {
//             console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
//         }
//     });
// }
//get billing option form body
@if (!empty(Session::get('customer_id')))
get_billing_option_body();
@endif

function get_billing_option_body() {
    var _token = "{{csrf_token()}}";
    var url = '{{url("billing-option-body")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: {_token:_token },
        success: function (data) {
            if ($('#accordion_section .panel_body').is(':visible')) {
                $("#accordion_section .panel_body").slideUp();
                $("#accordion_section .panel_header").children(".fa").removeClass('fa-minus-circle').hide();
            }
            $("#billing_option_header").children(".fa").show().addClass('fa-minus-circle');
            $("#billing_option_body").slideDown(500);
            $('#billing_option_body').html('');
            $('#billing_option_body').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}

function user_checkout_type(){
    $('#billing_option_header h5').text('Account & Billing Details');
    var url = '{{url("user-checkout-type")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: $('#user_checkout_type_form').serialize(),
        success: function (data) {
            if(data){
                $("#checkout_option_body").slideUp();
                $("#checkout_option_header").children(".fa").removeClass('fa-minus-circle').addClass('fa-plus-circle');

                $("#billing_option_header").children(".fa").show().addClass('fa-minus-circle');
                $("#billing_option_body").slideDown(500);
                $('#billing_option_body').html('');
                $('#billing_option_body').html(data);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}

function get_delivery_option_body() {
    var _token = "{{csrf_token()}}";
    var url = '{{url("delivery-option-body")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: {_token:_token },
        success: function (data) {

            $("#delivery_option_header").children(".fa").show().addClass('fa-minus-circle');
            $("#delivery_option_body").slideDown(250);
            $('#delivery_option_body').html('');
            $('#delivery_option_body').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}

function get_delivery_slot_body() {
    var _token = "{{csrf_token()}}";
    var url = '{{url("delivery-slot-body")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: {_token:_token },
        success: function (data) {

            $("#delivery_slot_header").children(".fa").show().addClass('fa-minus-circle');
            $("#delivery_slot_body").slideDown(250);
            $('#delivery_slot_body').html('');
            $('#delivery_slot_body').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}

function get_payment_method_body(){
    var _token = "{{csrf_token()}}";
    var url = '{{url("payment-method-body")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: {_token:_token },
        success: function (data) {

            $("#payment_option_header").children(".fa").show().addClass('fa-minus-circle');
            $("#payment_option_body").slideDown(250);
            $('#payment_option_body').html('');
            $('#payment_option_body').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}

function save_checkout_customer_data(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var url = '{{url("/account-registration")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: $('#checkout_account_form').serialize(),
        // dataType: "JSON",
        success: function (data) {
            if(data.success){
                
                var user_account = $('.user_account:checked').val();
                if(user_account == 'register'){
                    $('#billing_option_header h5').text('Billing Details');
                    var _token = "{{csrf_token()}}";
                    var url = '{{url("billing-option-body")}}';
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {_token:_token },
                        success: function (billing_data) {
                            if ($('#accordion_section .panel_body').is(':visible')) {
                                $("#accordion_section .panel_body").slideUp();
                                $("#accordion_section .panel_header").children(".fa").removeClass('fa-minus-circle').hide();
                            }
                            $("#billing_option_header").children(".fa").show().addClass('fa-plus-circle');
                            $("#billing_option_body").slideUp();
                            $('#billing_option_body').html('');
                            $('#billing_option_body').html(billing_data);
                            
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
                        }
                    });


                }

                console.log(data.same_address);
                if(data.same_address == 'yes'){
                    var _token = "{{csrf_token()}}";
                    var url = '{{url("delivery-slot-body")}}';
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {_token:_token },
                        success: function (slot_data) {
                            // $("#checkout_option_header").children(".fa").removeClass('fa-minus-circle').hide();
                            // $("#checkout_option_body").hide();
                            $("#delivery_slot_header").children(".fa").show().addClass('fa-minus-circle');
                            $("#delivery_slot_body").slideDown(250);
                            $('#delivery_slot_body').html('');
                            $('#delivery_slot_body').html(slot_data);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
                        }
                    });
                }else if(data.same_address == 'no'){
                    var _token = "{{csrf_token()}}";
                    var url = '{{url("delivery-option-body")}}';
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {_token:_token },
                        success: function (delivery_data) {

                            $("#delivery_option_header").children(".fa").show().addClass('fa-minus-circle');
                            $("#delivery_option_body").slideDown(250);
                            $('#delivery_option_body').html('');
                            $('#delivery_option_body').html(delivery_data);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
                        }
                    });
                }

            }else if(data.errors){
                $("#checkout_account_form").find('.error').text('');
                console.log(data.errors);
                $.each(data.errors, function (key, value) {
                    $('#checkout_account_form .form-group,#checkout_account_form .checkbox').find('.error_'+key).text(value);
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}
//Save billing info function
function save_billing_info(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var url = '{{url("store-address-details")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: $('#billing_form').serialize(),
        dataType: "JSON",
        success: function (data) {
            if(data.success){
                var _token = "{{csrf_token()}}";
                $.ajax({
                    url: '{{url("billing-option-body")}}',
                    type: "POST",
                    data: {_token:_token },
                    success: function (data) {
                        
                        $('#billing_option_body').html('');
                        $('#billing_option_body').html(data);
                        $("#billing_option_body").slideUp();
                        $("#billing_option_header").children(".fa").removeClass('fa-minus-circle').addClass('fa-plus-circle');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
                    }
                });
                if(data.same_address == 'yes'){
                    get_delivery_slot_body();
                }else{
                    get_delivery_option_body();
                }

            }else if(data.errors){
                $("#billing_form").find('.error').text('');
                console.log(data.errors);
                $.each(data.errors, function (key, value) {
                    $('#billing_form .form-group').find('.error_'+key).text(value);
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}

//save shipping info function
function save_shipping_info(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var url = '{{url("store-address-details")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: $('#shipping_form').serialize(),
        dataType: "JSON",
        success: function (data) {
            if(data.success){
                var _token = "{{csrf_token()}}";
                $.ajax({
                    url: '{{url("delivery-option-body")}}',
                    type: "POST",
                    data: {_token:_token },
                    success: function (data) {
                        
                        $('#delivery_option_body').html('');
                        $('#delivery_option_body').html(data);
                        $("#delivery_option_body").slideUp();
                        $("#delivery_option_header").children(".fa").removeClass('fa-minus-circle').addClass('fa-plus-circle');
                        get_delivery_slot_body();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
                    }
                });


            }else if(data.errors){
                $("#shipping_form").find('.error').text('');
                console.log(data.errors);
                $.each(data.errors, function (key, value) {
                    $('#shipping_form .form-group').find('.error_'+key).text(value);
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}

// save delivery slot function
function save_delivery_slot(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var url = '{{url("store-delivery-slot")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: $('#delivery_slot_form').serialize(),
        dataType: "JSON",
        success: function (data) {
            if(data.success){
                var _token = "{{csrf_token()}}";
                $.ajax({
                    url: '{{url("delivery-slot-body")}}',
                    type: "POST",
                    data: {_token:_token },
                    success: function (data) {
                        
                        $('#delivery_slot_body').html('');
                        $('#delivery_slot_body').html(data);
                        $("#delivery_slot_body").slideUp();
                        $("#delivery_slot_header").children(".fa").removeClass('fa-minus-circle').addClass('fa-plus-circle');
                        get_payment_method_body();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
                    }
                });


            }else if(data.errors){
                $("#delivery_slot_form").find('.error').text('');
                console.log(data.errors);
                $.each(data.errors, function (key, value) {
                    $('#delivery_slot_form .form-group').find('.error_'+key).text(value);
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}

function get_confirm_option_body(){
    var _token = "{{csrf_token()}}";
    var url = '{{url("confirm-option-body")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: {_token:_token },
        success: function (data) {
            $("#payment_option_body").slideUp();
            $("#payment_option_header").children(".fa").removeClass('fa-minus-circle').addClass('fa-plus-circle');

            $("#confirm_option_header").children(".fa").show().addClass('fa-minus-circle');
            $("#confirm_option_body").slideDown(250);
            $('#confirm_option_body').html('');
            $('#confirm_option_body').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}

function confirm_order(){
    var _token = "{{csrf_token()}}";
    var url = '{{url("confirm-order")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: {_token:_token },
        beforeSend: function () {
            $('#confirm_order_btn').addClass('m-loader m-loader--light m-loader--left');
        },
        complete: function() {
            $('#confirm_order_btn').removeClass('m-loader m-loader--light m-loader--left');
        },
        success: function (data) {
            if(data.error)
            {
                swal('Oops...',data.error,'error');
            }else{
                $('#checkout-content').html('');
                $('#checkout-content').html(data);
                sidebar_cart();
                total_cart_item();
                $('html,body').animate({
                    scrollTop:0
                });
            }
            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}

$('#accordion_section .panel_header .fa').on('click',function(){
    if ($('.panel_body').is(':visible')) {
        $(".panel_body").slideUp('slow');
        $(".fa").removeClass('fa-minus-circle');
        $(".fa").addClass('fa-plus-circle');
    }
    if ($(this).parent().next(".panel_body").is(':visible')) {
        $(this).parent().next(".panel_body").slideUp('slow');
        $(this).parent().children(".fa").removeClass('fa-minus-circle');
        $(this).parent().children(".fa").addClass('fa-plus-circle');
    } else {
        $(this).parent().next(".panel_body").slideDown('slow');
        $(this).parent().children(".fa").removeClass('fa-plus-circle');
        $(this).parent().children(".fa").addClass('fa-minus-circle');
    }
});










//update cart product function
function update_cart(rowId,qty){

    var _token = "{{csrf_token()}}";
    var url = '{{url("/update-cart")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: { rowId:rowId, qty:qty, _token:_token },
        dataType: "JSON",
        success: function (data) {
            sidebar_cart();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}

//Remove product from cart function
function remove_product_from_cart(rowId) {

    var _token = "{{csrf_token()}}";
    var url = '{{url("/remove-cart")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: { rowId: rowId, _token: _token },
        dataType: "JSON",
        success: function (data) {
            total_cart_item();
            sidebar_cart();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}


</script>
@endsection

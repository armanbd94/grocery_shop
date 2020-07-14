@extends('website.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('main_content')
<section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{url('/')}}"><strong><span class="mdi mdi-home"></span> Home</strong></a> <span class="mdi mdi-chevron-right"></span> <a href="#">My Cart</a>
            </div>
        </div>
    </div>
</section>
<section class="cart-page section-padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-body cart-table" id="cart-content"> </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')
<script>
cart_content();
$(document).on('submit','#coupon_form',function(e){
    e.preventDefault();
        $.ajax({
            url:"{{url('/coupon-check')}}",
            type: "POST",
            data: new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            beforeSend: function () {
                $('#coupon_form #apply_btn').addClass('m-loader m-loader--light m-loader--left');
            },
            complete: function() {
                $('#coupon_form #apply_btn').removeClass('m-loader m-loader--light m-loader--left');
            },
            success: function (data)
            {
                $("#coupon_form").find('.error').text(''); 
                if(data.success){
                    cart_content();
                }else if (data.msg) {
                    $("#coupon_form").find('.error').text(data.msg); 

                } else
                {
                    $.each(data.errors, function (key, value) {
                        $('#coupon_form').find('.error_' +key).text(value);
                    });

                }

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
            console.log('Error');
            }
        });
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
            console.log(data);
            sidebar_cart();
            cart_content();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
            alert('error');
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
            console.log(data);
            total_cart_item();
            sidebar_cart();
            cart_content();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
            alert('error');
        }
    });
}

//Load siderbar cart data function
function cart_content() {

    var _token = "{{csrf_token()}}";
    var url = '{{url("/cart-content")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: {_token: _token },
        success: function (data) {
            $('#cart-content').html('');
            $('#cart-content').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
        }
    });
}

</script>
@endsection
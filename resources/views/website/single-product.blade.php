@extends('website.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('stylesheet')
<link rel="stylesheet" href="{{asset('public/website-assets/plugins/owl-carousel/owl.carousel.css')}}">
<link rel="stylesheet" href="{{asset('public/website-assets/plugins/owl-carousel/owl.theme.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />    
@endsection

@section('main_content')
<section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {!! $product_data['breadcrumb_menu'] !!} <span class="mdi mdi-chevron-right"></span><a href="">{{$page_title}}</a>
            </div>
        </div>
    </div>
</section>
<section class="shop-single section-padding pt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="shop-detail-left">
                    <div class="shop-detail-slider">
                        <div id="sync1" class="owl-carousel">
                            @if (!empty($product_data['image_array']))
                                @if (count($product_data['image_array']) > 0)
                                    @foreach ($product_data['image_array'] as $image)
                                        <div class="item"><img alt="{{$page_title}}" src="{{asset(LOADING_ICON)}}" data-src="{{asset(PRODUCT_IMAGE_PATH.$image)}}" class="img-fluid img-center lazyload"></div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                        <div id="sync2" class="owl-carousel">
                             @if (!empty($product_data['image_array']))
                                @if (count($product_data['image_array']) > 0)
                                    @foreach ($product_data['image_array'] as $image)
                                        <div class="item"><img alt="{{$page_title}}" src="{{asset(LOADING_ICON)}}" data-src="{{asset(PRODUCT_IMAGE_PATH.$image)}}" class="img-fluid img-center lazyload"></div>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="shop-detail-right">
                    {{-- <span class="badge badge-success">50% OFF</span> --}}
                    <h2>{{$page_title}}</h2>
                    
                    <p class="offer-price"> Price : QAR {{number_format($product_data['primary_price'],2)}}</p>
                    
                    <div class="form-group">
                        <p class="offer-price mb-0">Quantity:</p>

                        <div class="qty-form-group">
                            <span class="input-group-btn cart-qty-minus"><button id="single-cart-qty-minus"  class="btn btn-number" type="button">-</button></span>
                            <input type="text" readonly max="100" min="1" value="1" id="single-product-quantity" class="form-control border-form-control form-control-sm input-number">
                            <span class="input-group-btn cart-qty-plus "><button id="single-cart-qty-plus"  class="btn btn-number" type="button">+</button>
                            </span>
                        </div>
                    </div>
                    @if(!empty($product_data['weightwise_price']))
                        @if (count($product_data['weightwise_price']) > 1)
                           
                        <h6><strong><span class="mdi mdi-approval"></span> Also Available In</strong> - {{implode(',',$product_data['available_weight'])}}</h6>
                        @endif
                    @endif
                    @if(!empty($product_data['weightwise_price']))
                        @if (count($product_data['weightwise_price']) >= 2)
                            <select class="form-control btn btn-secondary w-200 add-to-cart-btn" onchange="add_to_cart(this.value)">
                                <option value=""><i class="mdi mdi-cart-outline"></i> Add To Cart</option>
                                @foreach ($product_data['weightwise_price'] as $item)
                                    <option value="{{$item->id}}">Buy - {{$item->weight.$item->unitname}} - {{number_format($item->price,2)}}</option>
                                @endforeach
                            </select>
                        @else 
                            @foreach ($product_data['weightwise_price'] as $item)
                                    @if ($item->is_default == 1)
                                    <button  onclick="add_to_cart({{$item->id}})" type="button" class="btn btn-secondary btn-lg add-to-cart-btn cart_btn"><i class="mdi mdi-cart-outline"></i> Add To Cart</button>
                                @endif
                            @endforeach
                        @endif
                    @endif
                
                    
                    <div class="short-description">
                        <h5>
                            Quick Overview  
                            {{-- <p class="float-right">Availability: <span class="badge badge-success">In Stock</span></p> --}}
                        </h5>
                        <table class="table table-hover table-bordered">
                            @if (!empty($product_data['data']->brand_name))
                                <tr>
                                    <td>Brand</td>
                                    <td>{{$product_data['data']->brand_name}}</td>
                                </tr>
                            @endif
                            @if (!empty($product_data['data']->product_name))
                                <tr>
                                    <td>Name</td>
                                    <td>{{$product_data['data']->product_name}}</td>
                                </tr>
                            @endif
                            @if (!empty($product_data['data']->product_variation))
                                <tr>
                                    <td>Variation</td>
                                    <td>{{$product_data['data']->product_variation}}</td>
                                </tr>
                            @endif
                            @if (!empty($product_data['primary_weight']))
                                <tr>
                                    <td>Content</td>
                                    <td>{{$product_data['primary_weight']}}</td>
                                </tr>
                            @endif
                            

                        </table>
                    </div>
                    <h6 class="mb-3 mt-4">Why shop from My Grocery?</h6>
                    <div class="row">
                    <div class="col-md-6">
                        <div class="feature-box">
                            <i class="mdi mdi-truck-fast"></i>
                            <h6 class="text-secondary">Free Delivery</h6>
                            <p>Our delivery service is completely free.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="feature-box">
                            <i class="mdi mdi-basket"></i>
                            <h6 class="text-secondary">100% Guarantee</h6>
                            <p>We gives 100% Satisfaction Guarantee.</p>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            @if (!empty($product_data['data']->description ))
                <div class="col-md-12 pt-10" id="more-info">
                    <div class="card">
                        <div class="card-header">
                            <h4>MORE INFO</h4>
                        </div>
                        <div class="card-body text-justify">
                            {!! $product_data['data']->description !!}
                        </div>
                    </div>
                    
                </div>   
            @endif
            @if (!empty($product_data['related_products']) && count($product_data['related_products']) > 0)
            <div class="col-md-12 pt-10" id="more-info">
                <div class="card">
                    <div class="card-header">
                        <h4>RELATED PRODUCTS</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        @foreach($product_data['related_products'] as $value)
                            @php
                                $weightwise_price = Helper::get_product_weightwise_price($value->product_id);
                                $available_array = [];
                            @endphp
                            
                            <div class="col-md-4 ">
                                <div class="product">
                                    <div class="product-header">
                                        <a href="{{url('single-product',$value->product_slug)}}"><img class="lazyload img-fluid" src="{{asset(LOADING_ICON)}}" data-src="{{asset(PRODUCT_IMAGE_PATH.$value->featured_image)}}" alt="{{$value->product_name}}"></a>
                                    </div>
                                    <div class="product-body">
                                        <h5 class="product-title">
                                        <a href="{{url('single-product',$value->product_slug)}}">
                                        @if (!empty($value->brand_name))
                                        {{$value->brand_name.' '}}
                                        @endif
                                        @if (!empty($value->product_variation))
                                        {{$value->product_variation.' '}}
                                        @endif
                                        {{$value->product_name}} 
                                        @if(!empty($weightwise_price))
                                            @foreach ($weightwise_price as $item)
                                                @if ($item->is_default == 1)
                                                    {{' '.$item->weight.$item->unitname}}
                                                @endif
                                            @endforeach
                                        @endif
                                        </a>
                                        </h5>
                                    
                                    </div>
                                    <div class="product-footer">
                                        <h4 class=" product-price mb-0">
                                            @if(!empty($weightwise_price))
                                                @foreach ($weightwise_price as $item)
                                                    @if ($item->is_default == 1)
                                                        {{'QAR '.number_format($item->price,2)}}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </h4>
                                        

                                        <div  class="form-group
                                        <?php 
                                        if(!empty($weightwise_price)){
                                            if (count($weightwise_price) > 1){
                                                echo 'pt-20';
                                            }else{
                                                echo 'pt-45';
                                            }
                                        }
                                        ?>" id="cart_btn_section_{{$value->product_id}}">
                                            <span class="available">
                                            @if(!empty($weightwise_price))
                                                @if (count($weightwise_price) > 1)
                                                    @foreach ($weightwise_price as $item)
                                                        @if ($item->is_default == 2)
                                                            @php
                                                                $weight = $item->weight.$item->unitname;
                                                                array_push($available_array,$weight);
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    <strong><span class="mdi mdi-approval"></span>Also Available in</strong> - {{implode(', ',$available_array)}}
                                                @endif
                                            @endif
                                            </span>
                                            @if(!empty($weightwise_price))
                                                @if (count($weightwise_price) >= 2)
                                                    <select class="form-control btn btn-secondary w-200  color-white" onchange="add_to_cart(this.value)">
                                                        <option value="">&#xf217; Add To Cart</option>
                                                        @foreach ($weightwise_price as $item)
                                                            <option value="{{$item->id}}">Buy - {{$item->weight.$item->unitname}} - QAR {{number_format($item->price,2)}}</option>
                                                        @endforeach
                                                    </select>
                                                @else 
                                                    @foreach ($weightwise_price as $item)
                                                        @if ($item->is_default == 1)
                                                            <button  onclick="add_to_cart({{$item->id}})" type="button" class="btn btn-secondary w-200 py-10"><i class="mdi mdi-cart"></i> Add To Cart</button>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>   
            @endif
        </div>
    </div>
</section>
@endsection

@section('script')

<!--begin::Toastr Resources -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{asset('public/website-assets/plugins/lazysizes.min.js')}}"></script>
<!--end::Toastr Resources -->

<script>
$('#single-cart-qty-plus').click(function(){
    var quantity = parseInt($('#single-product-quantity').val());
    $('#single-product-quantity').val(quantity + 1);    
});
$('#single-cart-qty-minus').click(function(){
    var quantity = parseInt($('#single-product-quantity').val());
    if (quantity > 1) {
        $('#single-product-quantity').val(quantity - 1);
    }
});

 //Add product into cart function
function add_to_cart(id) {
    var qty = parseInt($('#single-product-quantity').val());
    var _token = "{{csrf_token()}}";
    var url = '{{url("/add-to-cart")}}';
    if(id && qty){
        $.ajax({
            url: url,
            type: "POST",
            data: { id: id, qty:qty, _token: _token },
            dataType: "JSON",
            success: function (data) {
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": false,
                    "positionClass": "toast-bottom-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "500",
                    "timeOut": "2000",
                    "extendedTimeOut": "500",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "slideDown",
                    "hideMethod": "slideUp"
                };

                if(data.success){
                    $('#single-product-quantity').val(1);
                    toastr.success(data.success);
                }else if(data.warning){
                    toastr.warning(data.warning);
                }else if(data.error){
                    toastr.error(data.error);
                }
                
                total_cart_item();
                sidebar_cart();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
            }
        });
    }
    
}

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
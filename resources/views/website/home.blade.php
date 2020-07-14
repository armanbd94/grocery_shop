@extends('website.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('stylesheet')
<!-- Owl Carousel -->
<link rel="stylesheet" href="{{asset('public/website-assets/plugins/owl-carousel/owl.carousel.css')}}">
<link rel="stylesheet" href="{{asset('public/website-assets/plugins/owl-carousel/owl.theme.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />    
@endsection

@section('main_content')
<section class="carousel-slider-main text-center border-top border-bottom bg-white">
    <div class="owl-carousel owl-carousel-slider">
        @if (!empty($baners))
            @foreach($baners as $banner)
                <div class="item">
                    <a href="{{$banner->link}}"><img class="img-fluid lazyload" src="{{asset(LOADING_ICON)}}" data-src="{{asset(BANNER_IMAGE_PATH.$banner->image)}}" alt="mygrocery slider image"></a>
                </div>
            @endforeach
        @endif
        
    </div>
</section>
<section class="shop-list section-padding pt-20">
    <div class="container">
        <div class="row">
            <div class="col-md-3 p-0 category_section">
                <div class="shop-filters  bg-white pt-20 bt-orange">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header card_header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <h4 class="title-text">Category <span class="mdi mdi-chevron-down float-right"></span></h4>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body card-shop-filters category_list_section">
                                    <!-- Product Category List -->
                                    <ul id="tree1" class="text-left  category-list">
                                        @include('website.include.category')
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="container">
                    <div class="row bg-white" id="tab-section">
                        <div class="col-md-3 col-sm-12 col-12 col-xs-12" id="header-title"><h4 class="font-size-20">TOP PRODUCTS</h4></div>
                        <div class="tab_section col-md-9 col-sm-12 col-xs-12 col-12">
                            <ul class="nav nav-pills mb-3 float-right home_page_tab" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-latest-tab" data-catid="0" onclick="get_active_tab_products(0)" data-toggle="pill" href="#pills-latest" role="tab" aria-controls="pills-latest" aria-selected="true">Latest</a>
                                </li>
                                @if (!empty($categories))
                                    @foreach ($categories as $category)
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-{{$category->category_name}}-tab" data-toggle="pill"  onclick="get_active_tab_products({{$category->id}})" href="#pills-{{$category->category_name}}" role="tab" aria-controls="pills-{{$category->category_name}}" aria-selected="false">{{$category->category_name}}</a>
                                    </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>    
                <div class="tab-content" id="pills-tabContent">
                    <div class="row product-list  no-gutters"></div>
                </div>

                    
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{asset('public/website-assets/plugins/lazysizes.min.js')}}"></script>
<!--end::Toastr Resources -->

<script>
// var catid = $("#pills-tab .nav-item a.active").data('catid');

get_active_tab_products(0);  

function get_active_tab_products(catid){
    var _token = "{{csrf_token()}}";
    $.ajax({
        url: "{{url('/active-tab-products')}}",
        type: "POST",
        data:{catid:catid,_token:_token},
        success: function (data) {
            $('.product-list').html('');
            $('.product-list').html(data);
            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR+'<br>'+ textStatus+'<br>'+errorThrown);
        }
    });
}

 //Add product into cart function
function add_to_cart(id,pid='') {

    var _token = "{{csrf_token()}}";
    var url = '{{url("/add-to-cart")}}';
    $.ajax({
        url: url,
        type: "POST",
        data: { id: id, _token: _token },
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
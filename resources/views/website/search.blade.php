@extends('website.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('stylesheet')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />    
@endsection

@section('main_content')
<section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{url('/')}}"><strong><span class="mdi mdi-home"></span> Home</strong></a> <span class="mdi mdi-chevron-right"></span><a href="">{{ucwords($page_title)}}</a>
            </div>
        </div>
    </div>
</section>
<section class="shop-list section-padding pt-20">
    <div class="container">
        <div class="row column_reverse">
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
                <div class="shop-head container bt-orange">
                    <div class="row py-20">
                        <div class="col-md-12 p-0  pl-10px">
                            <h5 class="mb-3">{{$page_title}} <span class="text-secondary">"{{$query}}"</span></h5>
                        </div>
                        <div class="col-md-6 col-sm-6 p-0 pl-10px">
                            <div class="input-group float-left mt-2 show_perpage">
                                <span class="mr-5px" id="show-text"><b>Show</b></span>
                                <select class="form-centrol btn btn-secondary float-left color-white w_130px" id="show_perpage">
                                    <option value="">Select Please</option>
                                    <option value="9" selected>9</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                </select> 
                                <span class="ml-5px"><b>Per Page</b></span>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 p-0 order_by  pl-10px">
                            <div class="float-right mt-2">
                                <span class="mr-5px"><b>Sort By</b></span>
                                <select class="form-centrol btn btn-secondary float-right color-white w_130px text-left bg-dark" id="order_by">
                                    <option value="">Select Please</option>
                                    <option value="1">Product Name (A to Z)</option>
                                    <option value="2">Product Name (Z to A)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>    
                <div class="row no-gutters" id="product_data">
                    {!! $product !!}
                </div>
                <input type="hidden" name="hidden_page" class="form-control" id="hidden_page" value="1" />
            </div>
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

function fetch_data(page='', show_perpage='', order_by=''){
    var query = "{{$query}}";
    var _token = "{{csrf_token()}}";
    var url = '{{url("search-product")}}';
    if(query){
        $.ajax({
            url: url,
            type: "GET",
            data: { query:query, page:page, show_perpage:show_perpage,order_by:order_by,_token:_token },
            success: function (data) {
                $('#product_data').html('');
                $('#product_data').html(data);
                $('html,body').animate({
                    scrollTop:130
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
            }
        });
    }
}

$(document).on('click', '.pagination a', function(event){
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    $('#hidden_page').val(page);
    var show_perpage = $('#show_perpage option:selected').val();
    var order_by = $('#order_by  option:selected').val();
    $('li').removeClass('active');
    $(this).parent().addClass('active');
    fetch_data(page, show_perpage, order_by);
});
$(document).on('change', '#show_perpage', function(event){
    event.preventDefault();
    var page = $('#hidden_page').val();
    var show_perpage = $(this).val();
    var order_by = $('#order_by  option:selected').val();
    fetch_data(page, show_perpage, order_by);
});
$(document).on('change', '#order_by', function(event){
    event.preventDefault();
    var page = $('#hidden_page').val();
    var show_perpage = $('#show_perpage option:selected').val();
    var order_by = $(this).val();
    fetch_data(page, show_perpage, order_by);
});


 //Add product into cart function
function add_to_cart(id) {

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

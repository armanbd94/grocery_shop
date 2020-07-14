@extends('website.master')

@section('title')
@if(!empty($data))
    {{ucwords($data->page_name)}}
@endif

@endsection

@section('main_content')
<section class="section-padding bg-dark inner-header">
    <div class="container">
       <div class="row">
          <div class="col-md-12 text-center">
             <h1 class="mt-0 mb-3 text-white">@if(!empty($data)){{ucwords($data->page_name)}}@endif</h1>
             <div class="breadcrumbs">
                <p class="mb-0 text-white"><a class="text-white" href="{{url('/')}}">Home</a>  /  <span class="text-success">@if(!empty($data)){{ucwords($data->page_name)}}@endif</span></p>
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- End Inner Header -->
<section class="shop-list section-padding pt-20">
    <div class="container">
        <div class="row">
            {{-- <div class="col-md-3 p-0 category_section">
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
            </div> --}}
            <div class="col-md-12">
                {{-- <div class="shop-head container bt-orange">
                    <div class="row py-20">
                        <div class="col-md-12 p-0  pl-10px">
                            <h5 class="mb-3">
                            @if(!empty($data))
                                {{ucwords($data->page_name)}}
                            @endif
                            </h5>
                        </div>
                    </div>
                </div>     --}}
                {{-- <div class="row" id="product_data">
                   <div class="col-md-12"> --}}
                    @if(!empty($data))
                         {!! $data->page_content !!}
                    @endif
                   {{-- </div>
                </div> --}}
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')

<script>
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

@extends('website.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('main_content')
<section class="section-padding bg-dark inner-header">
    <div class="container">
       <div class="row">
          <div class="col-md-12 text-center">
             <h1 class="mt-0 mb-3 text-white">{{$page_title}}</h1>
             <div class="breadcrumbs">
                <p class="mb-0 text-white"><a class="text-white" href="{{url('/')}}">Home</a>  /  <span class="text-success">{{$page_title}}</span></p>
             </div>
          </div>
       </div>
    </div>
 </section>
<section class="not-found-page section-padding">
    <div class="container">
       <div class="row">
          <div class="col-md-8 mx-auto text-center  pt-4 pb-5">
             <h1><img class="img-fluid" src="img/404.png" alt="404"></h1>
             <h1>Sorry! Page not found.</h1>
             <p class="land">Unfortunately the page you are looking for has been moved or deleted.</p>
             <div class="mt-5">
                <a href="{{url('/')}}" class="btn btn-success btn-lg"><i class="mdi mdi-home"></i> GO TO HOME PAGE</a>
             </div>
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

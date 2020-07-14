<?php 
    $subtotal = str_replace(',','',Cart::total(2,'.',','));
    $discount  = 0;
    $code = '';
    if(Session::get('coupon_data')){
        $code = Session::get('coupon_data')['coupon_code'];
        $coupon_discount = Session::get('coupon_data')['coupon_discount'];
        $discount = $subtotal * ($coupon_discount/100);
    }
    
    $total =  $subtotal - $discount;
?>
<div class="table-responsive" style="z-index:1;">
    <table class="table cart_summary">
        <thead class="cart_table_header">
            <tr>
                <th class="cart_product">Product</th>
                <th>Description</th>
                <th>Unit price</th>
                <th>Qty</th>
                <th class="action">Action</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @if (count(Cart::content()) > 0)
                @foreach (Cart::content() as $row)
                    <tr>
                        <td class="cart_product">
                            <a href="{{url('single-product',$row->options->slug)}}">
                                <img class="img-fluid" src="{{asset(PRODUCT_IMAGE_PATH.$row->options->image)}}" alt="{{$row->name}}">
                            </a>
                        </td>
                        <td class="cart_description">
                            <h5 class="product-name"><a class="color-black" href="{{url('single-product',$row->options->slug)}}">@if($row->options->brand) 
                            {{$row->options->brand.' '}} 
                            @endif 
                            {{$row->name.' '.$row->options->weight}}</a></h5>
                        </td>
                        <td class="price"><span class="color-black">QAR {{number_format($row->price,2)}}</span></td>
                        <td>
                            <div class="cart-qty-form-group d-flex">
                                <span class="input-group-btn sidebar-cart-qty-minus"><button onclick="cart_qty_minus('{{$row->rowId}}')" class="btn btn-number border-grey" type="button"><i class="mdi mdi-minus"></i></button></span>
                                <input type="text" readonly max="100" min="1" value="{{$row->qty}}" id="product_quantity_{{$row->rowId}}" class="form-control border-grey border-form-control form-control-sm input-number sidebar-input-qty">
                                <span class="input-group-btn sidebar-cart-qty-plus "><button onclick="cart_qty_plus('{{$row->rowId}}')"  class="btn btn-number border-grey" type="button"><i class="mdi mdi-plus"></i></button>
                                </span>
                            </div>
                        </td>
                        <td class="action">
                            <button type="button" class="btn btn-sm btn-danger"  onclick="remove_product_from_cart('{{$row->rowId}}')"><i class="mdi mdi-delete"></i></button>
                        </td>
                        <td class="price color-black"><span>QAR {{$row->total}}</span></td>
                    </tr>
                @endforeach
            @else 
                <tr><td colspan="6" class="text-center text-danger">Your cart is empty!</td></tr>
            @endif
        </tbody>
        @if (count(Cart::content()) > 0)
        
        <tfoot>
            <tr>
               
                <td class="text-right" colspan="5"><h6><strong>Sub Total:</strong></h6></td>
                <td class="text-danger" colspan="2"><h6><strong>QAR {{$subtotal}} </strong></h6></td>
            </tr>
            <tr>
                <td class="text-right" colspan="3">
                    <form class="form-inline float-left" method="POST" id="coupon_form">
                        @csrf
                        
                        <div class="form-group">
                        <input type="text" name="coupon_code" id="coupon_discount" value="{{$code}}" placeholder="Enter coupon code" class="form-control border-form-control form-control-sm">
                        </div>
                        <button class="btn btn-success float-left btn-sm" id="apply_btn" type="submit">Apply</button>
                        
                        <div class="error error_coupon_code text-danger  mx-sm-3 mb-2 text-left"></div>
                    </form>
                </td>
                <td class="text-right" colspan="2"><h6><strong>Discount:</strong></h6></td>
                <td class="text-danger" colspan="2"><h6><strong>- QAR {{number_format($discount,2)}} </strong></h6></td>
            </tr>
            <tr>
                <td class="text-right" colspan="5"><h6><strong>Total:</strong></h6></td>
                <td class="text-danger" colspan="2"><h6><strong>QAR {{number_format($total,2)}} </strong></h6></td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

<div class="col-md-12">


<a href="{{url('/')}}" class="btn btn-secondary float-left col-md-3" id="cs-btn">
<span class="float-left"><i class="mdi mdi-arrow-left-bold"></i> Continue Shopping </span>

</a>
@if (count(Cart::content()) > 0)
<a href="{{url('checkout')}}" class="btn btn-secondary float-right col-md-5">
<span class="float-left"> Proceed to Checkout </span>
<span class="float-right"><strong>QAR {{Cart::total(2,'.',',')}}</strong>
<span class="mdi mdi-arrow-right-bold"></span></span>
</a>
@endif
</div>


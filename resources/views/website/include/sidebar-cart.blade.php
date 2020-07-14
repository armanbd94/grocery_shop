<div class="cart-sidebar-header">
    <h5>
        My Cart <span class="text-success">({{count(Cart::content())}} item)</span> <a data-toggle="sidebarCart" class="float-right" href="javascript:void(0)"><i class="mdi mdi-close"></i>
        </a>
    </h5>
</div>
<div class="cart-sidebar-body">
    
        
    
    @if (count(Cart::content()) > 0)
        <table class="table" id="sidebar-cart-table">
        @foreach (Cart::content() as $row)
        <tr>
            <td width="20%"><a href="{{url('single-product',$row->options->slug)}}"><img class="img-fluid" style="width:70px;" src="{{asset(PRODUCT_IMAGE_PATH.$row->options->image)}}" alt="{{$row->name}}"></a></td>
            <td  width="70%">
                <table class="table">
                    <tr>
                        <td colspan="2"  class="p-0">
                            <h5 class="title">
                            <a href="{{url('single-product',$row->options->slug)}}">
                            @if($row->options->brand) 
                            {{$row->options->brand.' '}} 
                            @endif 
                            @if ($row->options->variation)
                                {{$row->options->variation.' '}}
                            @endif
                            {{$row->name.' '.$row->options->weight}}
                            </a>
                            </h5>
                        </td>
                    </tr>
                    <tr>
                        <td  class="p-0"><h6 class="price">Price</h6></td>
                        <td class="p-0"><h6 class="price">: QAR {{number_format($row->price,2)}}</h6></td>
                    </tr>
                    <tr>
                        <td class="p-0"><h6 class="quantity"> Qty</h6></td>
                        <td class="p-0">
                        <div class="sidebar-qty-form-group" style="line-height:0.5;">
                            <span class="input-group-btn sidebar-cart-qty-minus">: <button onclick="cart_qty_minus('{{$row->rowId}}')" class="btn btn-number border-grey" type="button"><i class="mdi mdi-minus"></i></button></span>
                            <input type="text" readonly max="100" min="1" value="{{$row->qty}}" id="product_quantity_{{$row->rowId}}" class="form-control border-grey border-form-control form-control-sm input-number sidebar-input-qty">
                            <span class="input-group-btn sidebar-cart-qty-plus "><button onclick="cart_qty_plus('{{$row->rowId}}')"  class="btn btn-number border-grey" type="button"><i class="mdi mdi-plus"></i></button>
                            </span>
                        </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-0"><h6><strong>Subtotal</strong></h6></td>
                        <td class="p-0"><h6><strong>: QAR {{number_format($row->total,2)}}</strong> </h6></td>
                    </tr>
                </table>
            </td>
            <td width="10%">
            <button type="button" class="btn btn-danger btn-sm" onclick="remove_product_from_cart('{{$row->rowId}}')"><i class="mdi mdi-delete"></i></button>
            </td>
        </tr>
        @endforeach
        </table>
    @else    
        <h5 class="text-center text-danger mt-5"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Your cart is empty!</h5>
    @endif
        
</div>

<div class="cart-sidebar-footer">
    <table class="table">
        <tr><td colspan="2"><h6><strong>Total : </strong> <strong class="float-right">QAR {{Cart::total(2,'.',',')}}</strong></h6></td></tr>
        <tr>
            <td><a href="{{url('cart')}}"  class="btn btn-secondary btn-block" type="button"><i class="mdi mdi-cart-outline"></i> View Cart</a></td>
            <td>
            @if (Cart::content()->count() > 0)
                <a href="{{url('checkout')}}"  class="btn btn-success btn-block" type="button"> <i class="mdi mdi-reply"></i> Checkout</a>
            @else
                <button class="btn btn-success btn-block disabled" type="button" disabled> <i class="mdi mdi-reply"></i> Checkout</button>
            @endif
            
            
            </td>
        </tr>
    </table>
</div>
<script>
$('[data-toggle="sidebarCart"]').on("click", function (e) {
    e.preventDefault();
    $("body").toggleClass("toggled");
});

</script>
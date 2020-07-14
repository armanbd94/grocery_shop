@if(!empty($customer_id) && !empty($billing_id) && !empty($shipping_id) && !empty($delivery_slot['delivery_date']))
<?php
    $subtotal = Cart::total();
    $discount  = 0;
    if(Session::get('coupon_data')){
        $coupon_discount = Session::get('coupon_data')['coupon_discount'];
        $discount = $subtotal * ( $coupon_discount/100);
    }
    $pre_total =  $subtotal - $discount;
    $total = $pre_total + $shipping_fee;

?>
<div class="row">
    <div class="col-md-12 table-responsive">
        @if (count(Cart::content()) > 0)
        <table class="table cart_summary col-md-12 mt-3">
            <thead class="cart_table_header">
                <tr>
                    <th class="cart_product">Product</th>
                    <th>Description</th>
                    <th class="text-right">Unit price</th>
                    <th>Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @if (count(Cart::content()) > 0)
                    @foreach (Cart::content() as $row)
                        <tr>
                            <td class="cart_product">
                                <a>
                                    <img class="img-fluid" src="{{asset(PRODUCT_IMAGE_PATH.$row->options->image)}}" alt="{{$row->name}}">
                                </a>
                            </td>
                            <td class="cart_description">
                                <h5 class="product-name"><a class="color-black">
                                @if($row->options->brand) 
                                {{$row->options->brand.' '}} 
                                @endif 
                                {{$row->name.' '.$row->options->weight}}</a></h5>
                            </td>
                            <td class="price text-right"><span class="color-black">QAR {{number_format($row->price,2)}}</span></td>
                            <td>
                                {{$row->qty}}
                            </td>

                            <td class="price color-black text-right"><span>QAR {{number_format($row->total,2)}}</span></td>
                        </tr>
                    @endforeach
                @else 
                    <tr><td colspan="6" class="text-center text-danger">Your cart is empty!</td></tr>
                @endif
            </tbody>
            @if (count(Cart::content()) > 0)
            <tfoot>
                <tr>
                    <td class="text-right" colspan="4"><h6><strong>Sub Total:</strong></h6></td>
                    <td class="text-right"><h6><strong>QAR {{number_format($subtotal,2)}} </strong></h6></td>
                </tr>
                @if ($discount > 0)
                <tr>
                    <td class="text-right" colspan="4"><h6><strong>Discount:</strong></h6></td>
                    <td class="text-right"><h6><strong> - QAR {{number_format($discount,2)}} </strong></h6></td>
                </tr>
                @endif
                
                <tr>
                    <td class="text-right" colspan="4"><h6><strong>Shipping Fee:</strong></h6></td>
                    <td class="text-right"><h6><strong>QAR {{number_format($shipping_fee ,2)}} </strong></h6></td>
                </tr>
                <tr>
                    <td class="text-right" colspan="4"><h6><strong>Total:</strong></h6></td>
                    <td class="text-right"><h6><strong>QAR {{number_format($total,2)}} </strong></h6></td>
                </tr>
            </tfoot>
            @endif
        </table>
        @endif
    </div>
    <div class="col-md-12">
        <button type="button"  class="btn btn-secondary mb-2 btn-lg pull-right" id="confirm_order_btn" onclick="confirm_order()">CONFIRM ORDER</button>
    </div>
    
</div>

@else 
<div></div>
@endif
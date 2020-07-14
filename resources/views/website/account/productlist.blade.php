@if(!empty($order_products) && count($order_products) > 0)
    @php
        $i=1;
        $product_total_price = [];
        $unavailable_product_total_price = [];
    @endphp
    @foreach ($order_products as $item)
        @php
            if ($item->available == 2){
                array_push($unavailable_product_total_price,$item->total);
            }
            array_push($product_total_price,$item->total);
            
            $image = Helper::product_image($item->product_id);
        @endphp
        <tr>
        <td  class="text-center">{{$i++}}</td>
        <td  class="text-left">
            <div class="">
                <div class="m-widget4__item">
                    <div class="m-widget4__img m-widget4__img--logo">
                        <img src="{{asset(PRODUCT_IMAGE_PATH.$image)}}" alt="{{$item->name}}" style="width:3.4rem;">
                    </div>
                    <div class="m-widget4__info">
                        <span class="m-widget4__title">
                            {{$item->name}} 
                        </span>
                        @if ($item->available == 2)
                        <br>
                        <span class="m-widget4__sub">
                            <span class="m-badge m-badge--wide m-badge--danger">Unavailable</span>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </td>
        <td  class="text-right">QAR {{number_format($item->price,2)}}</td>
        <td  class="text-center">{{$item->qty}}</td>
        <td  class="text-right">QAR {{number_format($item->total,2)}}</td>
        </tr>
    @endforeach
    @php
        $subtotal_price = array_sum($product_total_price) -  array_sum($unavailable_product_total_price);
        $discount_amount = 0;
        if($discount > 0){
            $discount_amount = $subtotal_price * ($discount/100);
        }

        $pre_total = $subtotal_price - $discount_amount;
        $total = $pre_total + $shipping_fee;
    @endphp
    <tr>
    <td colspan="4" class="text-right"><h6>Sub Total</h6></td>
    <td class="text-right"><h6>QAR {{number_format($subtotal_price,2)}}</h6></td>
    </tr>
    @if($discount_amount > 0)
    <tr>
    <td colspan="4" class="text-right text-danger"><h6>Discount</h6></td>
    <td class="text-right text-danger"><h6> - QAR {{number_format($discount_amount,2)}}</h6></td>
    </tr>
    @endif
    <tr>
        <td colspan="4" class="text-right text-danger"><h6>Shipping Fee</h6></td>
        <td class="text-right text-danger"><h6>QAR {{number_format($shipping_fee,2)}}</h6></td>
        </tr>
    <tr>
    <td colspan="4" class="text-right font-weight-bold text-primary"><h6>Total</h6></td>
    <td class="text-right font-weight-bold text-primary"><h6>QAR {{number_format($total,2)}}</h6></td>
    </tr>
@else 
    <tr><td class="text-center text-danger" colspan="7">No Data Available</td></tr>
@endif
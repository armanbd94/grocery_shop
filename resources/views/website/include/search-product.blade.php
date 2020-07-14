
@if (!empty($product_data) && count($product_data) > 0)
    @foreach($product_data as $value)
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
                            <div class="select_icon"> 
                                <i class="mdi mdi-cart posab"></i>   
                                <select class="form-control btn btn-secondary w-200  color-white option_cart" onchange="add_to_cart(this.value)">
                                    <option value="">Add To Cart</option>
                                    @foreach ($weightwise_price as $item)
                                        <option value="{{$item->id}}">Buy - {{$item->weight.$item->unitname}} - QAR {{number_format($item->price,2)}}</option>
                                    @endforeach
                                </select>
                            </div>    
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
@else
    <div class="col-md-12 text-center text-danger"><h6>No result found</h6></div>
@endif

<nav class="d-flex justify-content-center mt-4 col-md-12" id="pagination">
{!! $product_data->links() !!}
</nav>
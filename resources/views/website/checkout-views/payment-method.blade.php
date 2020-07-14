@if(!empty($customer_id) && !empty($billing_id) && !empty($shipping_id) && !empty($delivery_slot['delivery_date']))
<div class="row">
<div class="col-md-12">
<div class="radio py-20px">
    <label>
    <input type="radio" name="payment_address" value="existing" class="payment_address" checked="checked"> Cash on delivery.</label>
    
</div>
<img src="{{asset('public/website-assets/img/cash-on-delivery.jpg')}}" style="width:250px;" alt="Cash on delivery"/>
</div>
<div class="col-md-12">
<button type="button"  class="btn btn-secondary btn-lg pull-right" onclick="get_confirm_option_body()">NEXT</button>
</div>



</div>

@else 
<div></div>
@endif
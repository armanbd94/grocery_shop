<form method="post" id="billing_form" class="py-20px">
    @csrf
    
    @if (count($existing_address) > 0)
        <div>
            
            <div class="radio mb-10">
                <label>
                <input type="radio" name="payment_address" value="existing" class="payment_address" checked="checked">
                I want to use an existing address</label>
            </div>
            <div id="payment-existing" class="mb-10">
                <select name="address_id" class="form-control">
                    @foreach ($existing_address as $address)
                        <option 
                        @if (!empty($billing_id))
                            @if ($address->id == $billing_id)
                                selected
                            @endif
                        @elseif($address->is_default == 1)
                            selected
                        @endif
                            value="{{$address->id}}">{{$address->address.', Doha, Qatar'}}</option>
                    @endforeach
                </select>
                <span class="error error_address_id text-danger"></span>
            </div>
            <div class="radio mb-10">
                <label>
                <input type="radio" name="payment_address" class="payment_address" value="new">
                I want to use a new address</label>
            </div>
            <div id="new_billing_address" style="display:none;">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group required">
                            <label class="control-label">First Name</label>
                            <input name="first_name" class="form-control border-form-control" value="@if (Auth::guard('customer')->check()) {{Auth::guard('customer')->user()->first_name}} @endif" placeholder="First Name" type="text">
                            <span class="error error_first_name text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group required">
                            <label class="control-label">Last Name</label>
                            <input name="last_name" class="form-control border-form-control" value="@if (Auth::guard('customer')->check()) {{Auth::guard('customer')->user()->last_name}} @endif" placeholder="Last Name" type="text">
                            <span class="error error_last_name text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group required">
                            <label class="control-label" for="mobile_no">Mobile No.</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><img style="width:30px;margin-right:5px;" src="{{asset('public/website-assets/img/Qatar-flag.jpg')}}" alt="Qatar Flag"/> +974</span>
                                </div>
                                <input type="text" name="mobile_no" placeholder="Mobile Number" id="mobile_no" value="@if (Auth::guard('customer')->check()) {{Auth::guard('customer')->user()->mobile}} @endif" class="form-control"  autocomplete="false"> 
                            </div>
                            <span class="error error_mobile_no text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group required">
                            <label class="control-label">City</label>
                            <input class="form-control border-form-control" value="{{'Doha'}}" type="text" readonly>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group required">
                            <label class="control-label">District</label>
                           
                            <select name="district" id="district" class="form-control border-form-control">
                                <option value="">Select Please</option>
                                @if (!empty(Helper::district()))
                                @foreach (Helper::district() as $district)
                                <option value="{{$district->district_name}}">{{$district->district_name}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="error error_district text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group required">
                            <label class="control-label">Address</label>
                            <input type="text" name="address" class="form-control border-form-control"  placeholder="Address">
                            <span class="error error_address text-danger"></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group required">
                    <label class="control-label">First Name</label>
                    <input name="first_name" class="form-control border-form-control" value="@if (Auth::guard('customer')->check()) {{Auth::guard('customer')->user()->first_name}} @endif" placeholder="First Name" type="text">
                    <span class="error error_first_name text-danger"></span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group required">
                    <label class="control-label">Last Name</label>
                    <input name="last_name" class="form-control border-form-control" value="@if (Auth::guard('customer')->check()) {{Auth::guard('customer')->user()->last_name}} @endif" placeholder="Last Name" type="text">
                    <span class="error error_last_name text-danger"></span>
                </div>
            </div>
        </div>
        <div class="row">
            
            
            <div class="col-sm-4">
                <div class="form-group required">
                    <label class="control-label" for="mobile_no">Mobile No.</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><img style="width:30px;margin-right:5px;" src="{{asset('public/website-assets/img/Qatar-flag.jpg')}}" alt="Qatar Flag"/> +974</span>
                        </div>
                        <input type="text" name="mobile_no" placeholder="Mobile Number" id="mobile_no" value="@if (Auth::guard('customer')->check()) {{Auth::guard('customer')->user()->mobile}} @endif" class="form-control"  autocomplete="false">
                        
                    </div>
                    <span class="error error_mobile_no text-danger"></span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group required">
                    <label class="control-label">City</label>
                    <input class="form-control border-form-control" value="{{'Doha'}}" type="text" readonly>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group required">
                    <label class="control-label">District</label>
                    <select name="district" id="district" class="form-control border-form-control">
                        <option value="">Select Please</option>
                        <option value="">Select Please</option>
                        @if (!empty(Helper::district()))
                        @foreach (Helper::district() as $district)
                        <option value="{{$district->district_name}}">{{$district->district_name}}</option>
                        @endforeach
                        @endif
                    </select>
                    <span class="error error_district text-danger"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group required">
                    <label class="control-label">Address</label>
                    <input type="text" name="address" class="form-control border-form-control"  placeholder="Address">
                    <span class="error error_address text-danger"></span>
                </div>
            </div>
        </div>
    @endif 
    <div class="row">
        <div class="col-sm-12">
        <div class="pull-left">                              
            <input type="checkbox" name="same_address" id="same_address" 
            @if(!empty($billing_id) && !empty($shipping_id))
                @if($billing_id == $shipping_id) 
                    value='1' checked 
                @endif
            @else 
            value="2" 
            @endif > My delivery and billing addresses are the same.
        </div>
        <div class="pull-right">  
        <input type="hidden" name="address_type" value="billing"/>
        <button type="button"  class="btn btn-secondary btn-lg pull-right" onclick="save_billing_info()">NEXT</button>
        </div>
        </div>
    </div>
</form>
<script>
$('.payment_address').click(function () {
    if($(this).val() == 'existing'){
        $('#new_billing_address').hide();
        $('#payment-existing').show();
    }else if($(this).val() == 'new'){
        $('#payment-existing').hide();
        $('#new_billing_address').show();
    }
});
$("#same_address").on("change", function() {
    if ($(this).is(":checked")) {
        $(this).val(1);
    } else {
        $(this).val(2);
    }
});
</script>
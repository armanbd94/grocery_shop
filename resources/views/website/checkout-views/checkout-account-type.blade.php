<form method="post" id="checkout_account_form" class="py-20px">
@csrf
    <div class="form-group required" style="display:  none ;">
        <div class="col-sm-10">                            
            <div class="radio">
                <label>
                <input type="radio" name="group_type" value="{{$group_type}}" checked="checked">
                Default</label>
            </div>
            <div class="radio">
                <label>
                <input type="radio" name="redirect" value="2" checked="checked">
                Redirect</label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <fieldset id="account">
                <legend>Your Personal Details</legend>
                <div class="row">
                    <div class="form-group col-md-6 required">
                        <label class="control-label" for="first_name">First Name</label>
                        <input type="text" name="first_name" value="" placeholder="First Name" id="first_name" class="form-control">
                        <span class="error error_first_name text-danger"></span>
                    </div>
                    <div class="form-group col-md-6 required">
                        <label class="control-label" for="last_name">Last Name</label>
                        <input type="text" name="last_name" value="" placeholder="Last Name" id="last_name" class="form-control">
                                    <span class="error error_last_name text-danger"></span>
                    
                    </div>
                    <div class="form-group col-md-6 required">
                        <label class="control-label" for="email">E-Mail</label>
                        <input type="email" name="email" value="" placeholder="Email" id="email" class="form-control">
                                    <span class="error error_email text-danger"></span>
                    
                    </div>

                    <div class="form-group required col-md-6">
                        <label class="col-sm-2 control-label" for="mobile" style="display: inline;">Mobile No.</label>
                        <div c;ass="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><img style="width:30px;margin-right:5px;" src="{{asset('public/website-assets/img/Qatar-flag.jpg')}}" alt="Qatar Flag"/> +974</span>
                                </div>
                                <input type="text" name="mobile" placeholder="Mobile Number" id="mobile" class="form-control"  autocomplete="false">
                            </div>
                            <span class="error error_mobile text-danger"></span>
                        </div>
                    </div>
                </div>
            </fieldset>
            @if ($group_type == 1)
            <fieldset>
                <legend>Your Password</legend>
                <div class="row">
                    <div class="form-group col-md-6 required">
                        <label class="control-label" for="input-payment-password">Password</label>
                        <input type="password" name="password" value="" placeholder="Password" id="password" class="form-control">
                        <span class="error error_password text-danger"></span>
                    </div>
                    <div class="form-group col-md-6 required">
                        <label class="control-label" for="input-payment-confirm">Password Confirm</label>
                        <input type="password" name="password_confirmation" value="" placeholder="Password Confirm" id="password_confirmation" class="form-control">
                    </div>
                </div>
                
            </fieldset>
            @endif
            
        </div>
        <div class="col-sm-12">
            <fieldset id="address">
                <legend>Your Address</legend>
                <div class="row">
                    <div class="form-group col-md-6 required">
                        <label class="control-label" for="city">City</label>
                        <input type="text" name="city" value="{{'Doha'}}" placeholder="City" id="city" class="form-control">
                    </div>
                    <div class="form-group  col-md-6 required">
                        <label class="control-label" for="district">District</label>
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
                    <div class="form-group col-md-12 required">
                        <label class="control-label" for="address">Address</label>
                        <input type="text" name="address"  placeholder="Address" id="address" class="form-control">
                        <span class="error error_address text-danger"></span>
                    </div>
                </div>

            </fieldset>

        </div>
    </div>

    <div class="checkbox">
        <label>
        <input type="checkbox" name="same_address" id="same_address" value="2">
        My delivery and billing addresses are the same.</label>
    </div>
    @if ($group_type == 1)
    <div class="checkbox">
        <label>
        <input type="checkbox" name="agree" id="agree" value="">
        I have read and agree to the<a href="" class="agree"><b>Privacy Policy</b></a> </label>
        <br><span class="error error_agree text-danger"></span>
    </div>
    @endif
    <div class="buttons clearfix">
    <div class="pull-right"> 
        <button type="button" class="btn btn-secondary" onclick="save_checkout_customer_data()">Continue</button>
    </div>

</form>
 
<script>
$("#same_address").on("change", function() {
    if ($(this).is(":checked")) {
        $(this).val(1);
    } else {
        $(this).val(2);
    }
});
$("#agree").on("change", function() {
    if ($(this).is(":checked")) {
        $(this).val(1);
    } else {
        $(this).val('');
    }
});
</script>
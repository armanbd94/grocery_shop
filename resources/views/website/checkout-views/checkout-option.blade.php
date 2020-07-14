@if (empty(Session::get('customer_id')))
<div class="row">
    <div class="col-sm-6">
        <form method="post" id="user_checkout_type_form">
            @csrf
            <h2>New Customer</h2>
            <p>Checkout Options:</p>
            <div class="radio">
                <label> <input type="radio" class="user_account" name="user_account" value="register" checked="checked"> Register Account</label>
            </div>
            {{-- <div class="radio">
                <label> <input type="radio" class="user_account" name="user_account" value="guest"> Guest Checkout</label>
            </div> --}}
            <p>By creating an account you will be able to shop faster, be up to date on an order's status, and keep track of the orders you have previously made.</p>
            <button type="button" id="button-account" class="btn btn-secondary" onclick="user_checkout_type()">Continue</button>
        </form>
    </div>
    <div class="col-sm-6">
        <h2>Returning Customer</h2>
        <p>I am a returning customer</p>
        <form  action="{{url('/account-login')}}" method="post">
            @csrf
            <div class="form-group required" style="display:  none ;">
                <label class="control-label">Customer Group</label>
                <div class="">                            
                    <div class="radio">
                        <label>
                        <input type="radio" name="redirect" value="2" checked="checked">
                        Redirect</label>
                    </div>
                </div>
            </div>
            <div class="form-group required">
                <label class="control-label" for="email">Email</label>
                <input type="text" name="email" value="" placeholder="E-Mail" id="email" class="form-control">
                 @if ($errors->has('email'))
                    <span class="text-danger" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-password">Password</label>
                <input type="password" name="password" value="" placeholder="Password"  id="password" class="form-control">
                    @if ($errors->has('password'))
                        <span class="text-danger" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                <div class="forget-password"><a  href="">Forgotten Password</a></div>
            </div>
            <button type="submit" class="btn btn-secondary">Login</button>
        </form>
    </div>
</div>
@else 
<div></div>
@endif
<script>
$('.user_account').click(function(){
    if($(this).val() == 'register'){
        $('#billing_option_header h5').text('Account & Billing Details');
    }else if($(this).val() == 'guest'){
        $('#billing_option_header h5').text('Billing Details');
    }
});
</script>
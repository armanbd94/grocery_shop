@extends('website.master') 
@section('title') 
{{$page_title}}
@endsection
 
@section('main_content')
<section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{url('/')}}"><strong><span class="mdi mdi-home"></span> Home</strong></a>
                <span class="mdi mdi-chevron-right"></span><a href="{{url('account')}}">Account</a>
                <span class="mdi mdi-chevron-right"></span><a>Register</a>
            </div>
        </div>
    </div>
</section>
<section class="shop-list section-padding pt-20">
    <div class="container">
        <div class="row column_reverse">
            <div class="col-md-3 p-0 category_section">
                <div class="shop-filters  bg-white pt-20 bt-orange">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header card_header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <h4 class="title-text">Category <span class="mdi mdi-chevron-down float-right"></span></h4>
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body card-shop-filters category_list_section">
                                    <!-- Product Category List -->
                                    <ul id="tree1" class="text-left  category-list">
                                        @include('website.include.category')
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="shop-head container bt-orange">
                    <div class="row py-20">
                        <div class="col-md-12 p-0  pl-10px">
                            <h5 class="mb-3">{{$page_title}}</h5>
                        </div>
                    </div>
                </div>    
                <div class="row">
                   <div id="content" class="col-sm-9">
                        {{-- <h1>Register Account</h1> --}}
                        <p>If you already have an account with us, please login at the <a href="{{url('account-login')}}">login page</a>.</p>
                        @if (session('status'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> 
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('warning'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> 
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                {{ session('warning') }}
                            </div>
                        @endif
                        <form action="" method="post" id="register_account" class="form-horizontal"  autocomplete="off">
                            <fieldset id="account">
                            <legend>Your Personal Details</legend>
                                <div class="form-group required" style="display:  none ;">
                                    <label class="col-sm-2 control-label">Customer Group</label>
                                    <div class="col-sm-10">                            
                                        <div class="radio">
                                            <label>
                                            <input type="radio" name="group_type" value="1" checked="checked">
                                            Default</label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                            <input type="radio" name="redirect" value="1" checked="checked">
                                            Redirect</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group required row">
                                    <label class="col-sm-2 control-label" for="first_name">First Name</label>
                                    <div class="col-sm-10">
                                    <input type="text" name="first_name" value="" placeholder="First Name" id="first_name" class="form-control"  autocomplete="false">
                                    <span class="error error_first_name text-danger"></span>
                                    </div>
                                </div>
                                <div class="form-group required row">
                                    <label class="col-sm-2 control-label" for="last_name">Last Name</label>
                                    <div class="col-sm-10">
                                    <input type="text" name="last_name" value="" placeholder="Last Name" id="last_name" class="form-control"  autocomplete="false">
                                    <span class="error error_last_name text-danger"></span>
                                    </div>
                                </div>
                                <div class="form-group required row">
                                    <label class="col-sm-2 control-label" for="email">Email</label>
                                    <div class="col-sm-10">
                                    <input type="email" name="email" value="" placeholder="Email" id="email" class="form-control"  autocomplete="false">
                                    <span class="error error_email text-danger"></span>
                                    </div>
                                </div>
                                <div class="form-group required row">
                                    <label class="col-sm-2 control-label" for="mobile">Mobile No.</label>
                                    
                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><img style="width:30px;margin-right:5px;" src="{{asset('public/website-assets/img/Qatar-flag.jpg')}}" alt="Qatar Flag"/> +974</span>
                                            </div>
                                            <input type="text" name="mobile" placeholder="Mobile Number" id="mobile" class="form-control"  autocomplete="false">
                                        </div>
                                        <span class="error error_mobile text-danger"></span>
                                    </div>
                                </div>
                               
                            </fieldset>
                            <fieldset>
                                <legend>Your Password</legend>
                                <div class="form-group required row">
                                    <label class="col-sm-2 control-label" for="password">Password</label>
                                    <div class="col-sm-10">
                                    <input type="password" name="password" value="" placeholder="Password" id="password" class="form-control"  autocomplete="false">
                                    <span class="error error_password text-danger"></span>
                                    </div>
                                </div>
                                <div class="form-group required row">
                                    <label class="col-sm-2 control-label" for="password_confirmation">Password Confirm</label>
                                    <div class="col-sm-10">
                                    <input type="password" name="password_confirmation" value="" placeholder="Password Confirm" id="password_confirmation" class="form-control"  autocomplete="false">
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group required row">
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        
                                     <input type="checkbox" name="agree" value="1">  I have read and agree to the <a href="" class="agree"><b>Privacy Policy </b></a> 
                                    
                                    <br><span class="error error_agree text-danger"></span>
                                    </div>
                                    <div class="pull-right " id="register-btn-section">
                                        
                                        <button type="submit" id="register-btn"  class="btn btn-secondary">Continue</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
<script>

$('#register_account').on('submit', function(event){
    event.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url:"{{url('/account-registration')}}",
        type: "POST",
        data: new FormData(this),
        contentType:false,
        cache:false,
        processData:false,
        success: function (data) {

            if(data.success) {
                window.location = data.success;

            }else if(data.errors){
                $("#register_account").find('.error').text('');
                console.log(data.errors);
                $.each(data.errors, function (key, value) {
                    $('.form-group').find('.error_' +
                        key).text(value);
                });
                
            }

        },
        error: function (xhr, ajaxOptions,thrownError) {
            alert(xhr+'<br>'+ajaxOptions+'<br>'+thrownError);
        }
    });
});

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
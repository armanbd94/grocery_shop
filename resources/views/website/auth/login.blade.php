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
                <span class="mdi mdi-chevron-right"></span><a>Login</a>
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
                <div class="row  column_reverse">
                    <div class="col-sm-6 mtb-50">
                        <div class="well">
                            <h3>New Customer</h3>
                            <p><strong>Register Account</strong></p>
                            <p>By creating an account you will be able to shop faster, be up to date on an order's
                                status, and keep track of the orders you have previously made.</p>
                            <a href="{{url('/account-register')}}"  class="btn btn-secondary">Continue</a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="well">
                            <h3>Returning Customer</h3>
                            <p><strong>I am a returning customer</strong></p>
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
                            <form action="{{url('/account-login')}}" method="post">
                                @csrf
                                 <div class="form-group required" style="display:  none ;">
                                    <label class="col-sm-2 control-label">Customer Group</label>
                                    <div class="col-sm-10">                            
                                        <div class="radio">
                                            <label>
                                            <input type="radio" name="redirect" value="1" checked="checked">
                                            Redirect</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="control-label" for="input-email">E-Mail Address</label>
                                    <input type="email" name="email" placeholder="E-Mail Address"
                                        id="input-email" class="form-control">
                                    @if ($errors->has('email'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group required">
                                    <label class="control-label" for="input-password">Password</label>
                                    <input type="password" name="password" value="" placeholder="Password"
                                        id="input-password" class="form-control">
                                    @if ($errors->has('password'))
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group"><a href="{{url('account/forgot-password')}}">Forgotten Password?</a></div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-secondary">Login</button>
                                </div>
                                <div class="or-seperator"><i>or</i></div>
                                <div class="form-group d-flex justify-comtemt-space-betwwen">
                                        <a href="{{url('/customer-login','google')}}" class="col-md-6 p-2px">
                                            <button type="button" class="btn btn-google col-md-12" ><i class="fa fa-google"></i> Google </button> 
                                        </a>
                                        <a href="{{url('/customer-login','facebook')}}" class="col-md-6 p-2px">
                                            <button type="button" class="btn btn-facebook col-md-12"><i class="fa fa-facebook"></i> Facebook </button>
                                         </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
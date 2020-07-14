<nav class="navbar navbar-light navbar-expand-lg bg-dark bg-faded osahan-menu sticky">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{url('/')}}"> 
            @if(!empty(Helper::get_site_data()))
            <img class="navbar_logo" src="{{asset(LOGO_PATH.Helper::get_site_data()->site_logo)}}" alt="logo" > 
            @endif
        </a>
        <button class="navbar-toggler navbar-toggler-white" type="button" id="navtoggle">
        <span class="navbar-toggler-icon"></span>
        </button>




        <div class="navbar-collapse" id="navbarNavDropdown">
            <div class="navbar-nav mr-auto mt-2 mt-lg-0 margin-auto top-categories-search-main">
                <div class="top-categories-search">
                <form method="get" action="{{url('search')}}">
                    <div class="input-group">
                    <input class="form-control" name="query" placeholder="Search.." aria-label="Search products in Your City" type="text">
                    {{-- <input class="form-control" name="nothing" value="query"  type="hidden"> --}}
                    <span class="input-group-btn">
                    <button class="btn btn-secondary" type="submit"><i class="mdi mdi-file-find"></i> Search</button>
                    </span>
                    </div>
                </form>
                    
                </div>
            </div>
            <div class="my-2 my-lg-0">
                <ul class="list-inline main-nav-right">
                    
                    <li class="list-inline-item cart-btn dropdown">
                        
                        <a class="btn btn-link border-none  dropdown-toggle user_circle_btn" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @if (Auth::guard('customer')->check())
                        <span class="logged_in"></span> 
                        @endif
                        <i class="mdi mdi-account-circle"></i> My Account
                        </a>
                        <div class="dropdown-menu">
                        
                        @if (Auth::guard('customer')->check())
                        <a class="dropdown-item" href="{{url('account/profile')}}"><i class="mdi mdi-chevron-right" aria-hidden="true"></i>  My Account</a>
                        <a class="dropdown-item" href="{{url('account/order-history')}}"><i class="mdi mdi-chevron-right" aria-hidden="true"></i>  Order History</a>
                        <a class="dropdown-item" style="cursor:pointer;"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="mdi mdi-chevron-right" aria-hidden="true"></i>  Logout</a>
                        <form id="logout-form" action="{{url('/account-logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        @else
                        <a class="dropdown-item" href="{{url('account-register')}}"><i class="mdi mdi-chevron-right" aria-hidden="true"></i>  Register</a>
                        <a class="dropdown-item" href="{{url('account-login')}}"><i class="mdi mdi-chevron-right" aria-hidden="true"></i>  Login</a>
                        @endif
                        <a class="dropdown-item" href="{{url('cart')}}"><i class="mdi mdi-chevron-right" aria-hidden="true"></i>  Shopping Cart </a>
                        <a class="dropdown-item" href="{{url('checkout')}}"><i class="mdi mdi-chevron-right" aria-hidden="true"></i>  Checkout</a> 
                    </li>
                    
                    @if(Request::path() != 'cart')
                    <li class="list-inline-item cart-btn">
                    <a href="#" data-toggle="offcanvas" class="btn btn-link border-none"><i class="mdi mdi-cart"></i> My Cart <small class="cart-value" id="cart-value">0</small></a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>

<nav class="codehim-dropdown orange">
    <div class="container">
        <ul class="dropdown-items">
            {{-- <a href="#" class="close_btn" ><i class="fa fa-close"></a> --}}
        
        <li>
                <div id="_mobile_user_info"><div class="user-info language-selector innovatory-user-info">                    
                <div class="user-info-wrap hidden-lg-up">
                    <i class="fa fa-user-circle user-icon" aria-hidden="true"></i>
                    {{-- <a id="menu_close" class="close-sidebar pull-right"><i class="mdi mdi-close"></i></a>  --}}
                    <div class="user-info-btn">

                        @if (Auth::guard('customer')->check())  
                            <a class="account" href="" title="View my customer account" rel="nofollow">{{Auth::guard('customer')->user()->first_name.' '.Auth::guard('customer')->user()->last_name}}</a>
                            <p class="cust-mail">{{Auth::guard('customer')->user()->email}}</p>
                            <a class="logout"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out">&nbsp;Logout</i></a>
                            <form id="logout-form" action="{{url('/account-logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @else    
                            <a href="{{url('account-login')}}" title="Log in to your customer account" rel="nofollow"><i class="mdi mdi-login"></i>&nbsp;Login</a>
                            <a class="register" href="{{url('account-register')}}"><i class="mdi mdi-account-key"></i>&nbsp;Register</a>
                        @endif   

                        
                    </div>
                    </div> 
                    </div>
                </div> 

        </li>
        <li class="home-link">
            <a href="{{url('/')}}"><i class="fa fa-home"></i></a>
        </li>
        <li  class="home-link-text"> 
            <a class="main-links" href="{{url('/')}}">Home</a>
        </li>
            
        @if (!empty(Helper::website_pages()))
            @foreach(Helper::website_pages() as $page)
                <li> 
                <a class="main-links" href="{{url('content',$page->page_url)}}">{{ucwords($page->page_name)}}</a>
                </li>
            @endforeach
        @endif
        
        <li> <a class="main-links" href="{{url('contact')}}">
            Contact</a>
        </li>

        <li class="category_menu">
        <span class="dropdown-heading">Category</span>
                <ul class="menu-items text-left  category-list"  id="category_tree">
                    @include('website.include.category')
                </ul>   
        </li>
        
        </ul>
    </div>   
</nav>

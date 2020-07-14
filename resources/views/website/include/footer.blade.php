<!-- Begin :: Upper Footer Section-->
<section class="section-padding bg-white border-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-6">
                <div class="feature-box">
                    <i style="color:#e96125" class="mdi mdi-truck-fast"></i>
                    <h6>Quick Delivery</h6>
                    <p>First delivery service is completely free.</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="feature-box">
                    <i style="color:#e96125" class="mdi mdi-basket"></i>
                    <h6>100% Satisfaction Guarantee</h6>
                    <p>We gives 100% Satisfaction Guarantee.</p>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="feature-box">
                    <i style="color:#e96125" class="mdi mdi-tag-heart"></i>
                    <h6>Great Daily Deals</h6>
                    <p>We try to make all of our delas great.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Begin :: Upper Footer Section-->

<!-- Begin :: Footer Widget Section -->
<section class="section-padding footer bg-white border-top">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <h4 class="mb-5 mt-0">
                <a class="logo" href="{{url('/')}}">
                @if (!empty(Helper::get_site_data()->footer_logo))
                <img class="footer-logo" src="{{asset(LOGO_PATH.Helper::get_site_data()->footer_logo)}}" alt="My Grocery">
                @endif
                </a>
                </h4>
               <p class="mb-0">{{Helper::get_site_data()->website_footer_text}}</p>
            </div>
            <div class="col-lg-2 col-md-2">
                <h6 class="mb-4">CATEGORIES</h6>
                <ul>
                @if (!empty(Helper::parent_category()))
                    @foreach(Helper::parent_category() as $category)
                        <li><a href="{{url('category',$category->category_slug)}}">{{$category->category_name}}</a></li>
                    @endforeach
                @endif
                <ul>
            </div>
            <div class="col-lg-2 col-md-2">
                <h6 class="mb-4">OUR PAGES</h6>
                <ul>
                 @if (!empty(Helper::website_pages()))
                    @foreach(Helper::website_pages() as $page)
                        <li> 
                        <a href="{{url('content',$page->page_url)}}">{{ucwords($page->page_name)}}</a>
                        </li>
                    @endforeach
                @endif
               
                <ul>
            </div>
            <div class="col-lg-3 col-md-3">
                <h6 class="mb-4">CONTACT</h6>
                <div class="app">
                <p class="mb-0"><a href="{{url('/')}}"><i class="mdi mdi-cellphone-iphone"></i>{{Helper::get_site_data()->contact_no}}</a></p>
                <p class="mb-0"><a href="{{url('/')}}"><i class="mdi mdi-email"></i> {{Helper::get_site_data()->contact_email}}</a></p>
                <p class="mb-0"><a href="{{url('/')}}"><i class="mdi mdi-map-marker"></i> {{Helper::get_site_data()->contact_address}}</a></p>
                </div>
            </div>
            <div class="col-lg-2 col-md-3">
                <h6 class="mb-4">GET IN TOUCH</h6>
                
                <div class="footer-social">
                    <a class="btn-facebook btn-secondary" href="{{json_decode(Helper::get_site_data()->social_media)->facebook}}"><i class="mdi mdi-facebook"></i></a>
                    <a class="btn-twitter btn-secondary" href="{{json_decode(Helper::get_site_data()->social_media)->twitter}}"><i class="mdi mdi-twitter"></i></a>
                    <a class="btn-instagram btn-secondary" href="{{json_decode(Helper::get_site_data()->social_media)->instagram}}"><i class="mdi mdi-instagram"></i></a>
                    
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End :: Footer Widget Section -->

<!-- Begin :: Lower Footer Copyright Section -->
<section class="pt-4 pb-4 footer-bottom">
    <div class="container">
        <div class="row no-gutters">
            <div class="col-lg-6 col-sm-6">
                <p class="mt-1 mb-0">&copy; Copyright 2019 <strong class="text-dark">@if(!empty(Helper::get_site_data())) {{' | '.Helper::get_site_data()->website_title}} @endif</strong>. All Rights Reserved<br>
                </p>
            </div>
            {{-- <div class="col-lg-6 col-sm-6 text-right">
                <img alt="osahan logo" src="{{asset('public/website-assets/img/payment_methods.png')}}">
            </div> --}}
        </div>
    </div>
</section>
<div id="back-to-top" data-spy="affix" data-offset-top="10" class="back-to-top affix">
    <button class="btn btn-primary" title="Back to Top">
        <i class="fa fa-angle-up"></i>
    </button>
</div>
<!-- End :: Lower Footer Copyright Section -->
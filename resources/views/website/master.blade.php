<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <!-- NECESSARY META TAGS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    

    <!-- WEBSITE TITLE -->
    <title>@yield('title') @if(!empty(Helper::get_site_data())) {{' | '.Helper::get_site_data()->website_title}} @endif</title>

    <meta name="author" content="Sazzad Hossain">              <!-- WEBSITE AUTHOR NAME -->
    @yield('seo_meta_tag')                       <!-- ADD SEO META TAGS DYNAMICALLY -->
    <meta name="robots" content="index, follow"> <!-- WEBSITE CRAWLED BY SEARCH BOT -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- FAVICON -->
    @if(!empty(Helper::get_site_data()))
    <link rel="shortcut icon" href="{{asset(LOGO_PATH.Helper::get_site_data()->favicon_icon)}}" />
    @endif

    <!-- Bootstrap core CSS -->
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous"> --}}
    <link href="{{asset('public/website-assets/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link href="{{asset('public/website-assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Material Design Icons -->
    <link href="{{asset('public/website-assets/plugins/icons/css/materialdesignicons.min.css')}}" media="all" rel="stylesheet" type="text/css" />

     <!-- Custom styles for this template -->
    <link href="{{asset('public/website-assets/css/osahan.min.css')}}" rel="stylesheet">

    {{-- <link href="{{asset('public/website-assets/css/animate.css')}}" rel="stylesheet"> --}}
    <link href="{{asset('public/website-assets/css/codehim-dropdown.css')}}" rel="stylesheet">

    
    {{-- <link rel="stylesheet" href="{{asset('public/website-assets/plugins/touchspin/jquery.bootstrap-touchspin.min.css')}}"> --}}
    
    @yield('stylesheet') <!-- FOR ADDING STYLE SHEET DYNAMICALLY -->

    <!-- Bootstrap core JavaScript -->
    <script src="{{asset('public/website-assets/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('public/website-assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
</head>

<body>
    <div class="preload"></div>
    @include('website.include.header') <!-- INCLUDED HEADER SECTION -->
    @yield('main_content')             <!-- ADD MAIN CONTENT DYNAMICALLY -->
    @include('website.include.footer') <!-- INCLUDED FOOTER SECTION -->

    <!-- Sidebar Cart  -->
    <div class="cart-sidebar"></div>


    <!-- Custom Js -->
    <script src="{{asset('public/website-assets/js/main.js')}}"></script>
    <script src="{{asset('public/website-assets/js/codehim.dropdown.js')}}"></script>
    
   <!-- Owl Carousel -->
    <script src="{{asset('public/website-assets/plugins/owl-carousel/owl.carousel.js')}}"></script>
    <script src="{{asset('public/website-assets/js/custom.min.js')}}"></script>
    <!--begin::Toastr Resources -->
    @yield('script') <!-- FOR ADDING JS SCRIPT DYNAMICALLY -->

    <script>
    
    total_cart_item();
    sidebar_cart();

    //Load siderbar cart data function
    function sidebar_cart() {
        var _token = "{{csrf_token()}}";
        var url = '{{url("/sidebar-cart")}}';
        $.ajax({
            url: url,
            type: "POST",
            data: {_token: _token },
            success: function (data) {
                $('.cart-sidebar').html('');
                $('.cart-sidebar').html(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
            }
        });
    }
    function total_cart_item() {
        var _token = "{{csrf_token()}}";
        var url = '{{url("/total-cart-item")}}';
        $.ajax({
            url: url,
            type: "POST",
            data: {_token: _token },
            success: function (data) {
                if(data.total_item){
                    $('#cart-value').text(data.total_item)
                }else{
                    $('#cart-value').text('0')
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
            }
        });
    }

    function handlePreloader() {
        if($('.preload').length){
            $('.preload').delay(220).fadeOut(500);
        }
    }
    $(window).on('load', function() {
        handlePreloader();
    });

    </script>

    <script>
        
        $(window).scroll(function(){
        var sticky = $('.sticky'),
            scroll = $(window).scrollTop();
        if (scroll >= 50) sticky.addClass('fixed');
        else sticky.removeClass('fixed');
        });
        $(document).ready(function() {
			// WOW animation initialize
		//	new WOW().init();
        });
        
        

        $(".codehim-dropdown").CodehimDropdown({

            // red, yellow, blue, green, orange, brown, teal
            // purple, indigo, cyan, light-green, amber, gray
            // black, blue-gray, lime, light-blue, deep-purple
            // deep-pink, deep-brown
            skin: "orange",

            // when a dropdown opens, auto closes the others
            slideUpOther: true,
            sticky: false,
            subListAnimation: "fadeInUp",
            dimOverlay: true,
            offCanvasSpeed: "default",           
            offCanvasDirection: "left",           
            offCanvasWidth: 290

            });

</script>
    
    
</body>
</html>

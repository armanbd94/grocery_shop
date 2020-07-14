<!DOCTYPE html>
<html lang="en" >
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>
			@yield('title') | @if(!empty(Helper::get_site_data())) {{Helper::get_site_data()->website_title}} @endif 
		</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!--begin::Fontawesome 5 -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
        <!--end::Fontawesome -->
        
		<!--begin::Chosen Select Vendors -->
		<link href="{{asset('public/dashboard-assets/vendors/custom/chosen/bootstrap-chosen.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Chosen Select Vendors -->

		<!--begin::Base Styles -->  
		<link href="{{asset('public/dashboard-assets/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('public/dashboard-assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Base Styles -->
		@if(!empty(Helper::get_site_data()))
		<link rel="shortcut icon" href="{{asset(LOGO_PATH.Helper::get_site_data()->favicon_icon)}}" />
		@endif
		<!--begin::Base Scripts -->
		<script src="{{asset('public/dashboard-assets/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
		<script src="{{asset('public/dashboard-assets/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
		<!--end::Base Scripts -->   
	</head>
	<!-- end::Head -->

    <!-- begin::Body -->
	<body  class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  style="background-image: url({{asset(UPLOAD_PATH.'white-bg-image.png')}});background-size: cover;background-position: center;background-repeat: no-repeat;">
	    <!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-3" id="m_login">
				<div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
					<div class="m-login__container">
						<div class="m-login__logo js-tilt" data-tilt>
							<a href="{{url('/admin')}}">
								@if (!empty(Helper::get_site_data()->footer_logo))
									<img src="{{asset(LOGO_PATH.Helper::get_site_data()->footer_logo)}}" style="width: 200px;">
								@else
									<h1 class="font-weight-bold">ADMIN</h1>
								@endif								
							</a>
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
		</div>  
		
		<!-- START:: SHOW LOADING IMAGE ON LOAD BODY -->
		<div id="loader"></div>
		<script>
		$(window).on('load', function() {
			if($('#loader').length){
				$('#loader').delay(220).fadeOut(500);
			}
		});
		</script>
		<!-- END:: SHOW LOADING IMAGE ON LOAD BODY -->
	</body>
	
	<!-- end::Body -->
</html>
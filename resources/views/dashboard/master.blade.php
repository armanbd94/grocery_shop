<!DOCTYPE html>
<html lang="en" >
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>
			@yield('title') | @if(!empty(Helper::get_site_data())) {{Helper::get_site_data()->website_title}} @endif
		</title>
		<meta name="description" content="AR Base For Management Site.">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		@if(!empty(Helper::get_site_data()))
		<link rel="shortcut icon" href="{{asset(LOGO_PATH.Helper::get_site_data()->favicon_icon)}}" />
		@endif
        <!--begin::Fontawesome 5 -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
		<!--end::Fontawesome -->

		<!--begin::Datatable Vendors -->
		<link href="{{asset('public/dashboard-assets/vendors/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Datatable Vendors -->

		<!--begin::Chosen Select Vendors -->
		<link href="{{asset('public/dashboard-assets/vendors/custom/chosen/bootstrap-chosen.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Chosen Select Vendors -->
		@yield('style_sheet')
		<!--begin::Base Styles -->  
		
		<link href="{{asset('public/dashboard-assets/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('public/dashboard-assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		
		<!--end::Base Styles -->

		<!--begin::Base Scripts -->
		<script src="{{asset('public/dashboard-assets/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
		<script src="{{asset('public/dashboard-assets/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
		<!--end::Base Scripts -->   
		
	</head>
	<!-- end::Head -->

    <!-- end::Body -->
	<body  class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">

			@include('dashboard.includes.header')	  <!-- site header content        -->

			<!-- begin::Body -->
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
				
				@include('dashboard.includes.sidebar') <!-- site left sidebar content -->
				@yield('main_content')                 <!-- site main body content    -->

			</div>
			<!-- end:: Body -->

			@include('dashboard.includes.footer')      <!-- site footer content       -->

		</div>
		<!-- end:: Page -->

		<!-- begin:: CRUD operation loader -->
		<div class="ajax_loading">
			<img src="{{asset('public/dashboard-assets/img/loading.svg')}}" alt="loading..." />
		</div>
		<!-- end:: CRUD operation loader -->

		<!-- begin:: Page Preloader -->
		<div id="loader"></div>
		<!-- end:: Page Preloader -->

		<!-- begin::Scroll Top -->
		<div id="m_scroll_top" class="m-scroll-top">
			<i class="la la-arrow-up"></i>
		</div>
		<!-- end::Scroll Top -->

		

		<!--begin::Page Vendors -->
		<script src="{{asset('public/dashboard-assets/vendors/custom/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
		<script src="{{asset('public/dashboard-assets/demo/default/custom/crud/datatables/extensions/buttons.js')}}" type="text/javascript"></script>
		<!--end::Page Vendors -->  

		<!--begin::Chosen Select Vendors -->
		<script src="{{asset('public/dashboard-assets/vendors/custom/chosen/chosen.jquery.js')}}" type="text/javascript"></script>
		<!--end::Chosen Select Vendors -->   

		<!--begin::Toastr Resources -->
		<script src="{{asset('public/dashboard-assets/demo/default/custom/components/base/toastr.js')}}" type="text/javascript"></script>
		<!--end::Toastr Resources -->

		<!--begin::Sweet Alert Js -->
		<script src="{{asset('public/dashboard-assets/demo/default/custom/components/base/sweetalert2.js')}}" type="text/javascript"></script>
		<!--end::Sweet Alert Js -->

		@yield('script')
		
		<script type="text/javascript">
		$('.chosen-select').chosen({width: "100%"});   
		toastr.options = {
			"closeButton": true,
			"debug": false,
			"newestOnTop": true,
			"progressBar": true,
			"positionClass": "toast-top-right",
			"preventDuplicates": true,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "5000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		};
		@if (Session::has('success'))
			toastr.success("<?= Session::get('success');?>","SUCCESS MESSAGE");
		@endif
		@if (Session::has('status'))
			toastr.success("<?= Session::get('status');?>","SUCCESS MESSAGE");
		@endif
		@if (Session::has('error'))
			toastr.error("<?= Session::get('error');?>","ERROR MESSAGE");
		@endif
		@if (Session::has('warning'))			
			toastr.warning("<?= Session::get('warning');?>","WARNING MESSAGE");
		@endif
		@if (Session::has('info'))			
			toastr.info("<?= Session::get('info');?>","INFORMATION MESSAGE");
		@endif
		</script>

		<script>
		$(document).ready(function(){
			$('#m_topbar_notification_icon .m-badge--dot').hide();
			$("#m_topbar_notification_icon .m-nav__link-icon").removeClass("m-animate-shake");
			$("#m_topbar_notification_icon .m-nav__link-badge").removeClass("m-animate-blink");

			var id = $(".notification_tab a.active").data('id');			
			get_notification(id);		//get_notification() function initialized to on load document fetch data	
		});
		//top header notification data fetcher function
		function get_notification(id) {
			if(id){
				if(id == 1 || id == 2){
					$.ajax({
						url: "{{url('/get-notification')}}/"+id,
						type: "GET",
						dataType: "JSON",
						success: function (data) {

							console.log(data);
							if(id == 1){
								$('#message .msg_list').html(data.notification_list);
							}else if(id == 2){
								$('#order .order_list').html(data.notification_list);
							}
							if(data.total_notification){
								$('.total_notification').text(data.total_notification+' New');
							}
							if(data.total_notification > 0){
								$('#m_topbar_notification_icon .m-badge--dot').show();
								$("#m_topbar_notification_icon .m-nav__link-icon").addClass("m-animate-shake");
								$("#m_topbar_notification_icon .m-nav__link-badge").addClass("m-animate-blink");
							}
							if(data.total_msg == 0){
								
								$('.msg_badge').removeClass('m-badge--accent');
								$('.msg_badge').addClass('m-badge--danger');
								$('.msg_badge').text(data.total_msg);
							}else{
								$('.msg_badge').removeClass('m-badge--danger');
								$('.msg_badge').addClass('m-badge--accent');
								$('.msg_badge').text(data.total_msg);
							}
							if(data.total_order == 0){
								
								$('.order_badge').removeClass('m-badge--accent');
								$('.order_badge').addClass('m-badge--danger');
								$('.order_badge').text(data.total_order);
							}else{
								$('.order_badge').removeClass('m-badge--danger');
								$('.order_badge').addClass('m-badge--accent');
								$('.order_badge').text(data.total_order);
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							console.log(jqXHR+'<br>'+textStatus+'<br>'+errorThrown);
						}
					});
				}
			}	
		}
		</script>
		
		<script>
		//Page Preloader js code
		$(window).on('load', function() {
			if($('#loader').length){
				$('#loader').delay(220).fadeOut(500);
			}
		});
		</script>
	</body>
	<!-- end::Body -->
</html>

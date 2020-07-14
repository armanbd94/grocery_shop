@extends('dashboard.master')

@section('title')
{{ucwords($page_title)}}
@endsection
@section('style_sheet')
<link href="{{asset('public/dashboard-assets/vendors/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('main_content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
	<!-- BEGIN: Subheader -->
	<div class="m-subheader ">
		<div class="d-flex align-items-center">
			<div class="mr-auto">
				<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
					<li class="m-nav__item m-nav__item--home">
					<a href="{{url('/')}}" class="m-nav__link m-nav__link--icon">
							<i class="m-nav__link-icon la la-home"></i>
							<span class="m-nav__link-text">
								{{$page_title}}
							</span>
						</a>
					</li>
					{{-- <li class="m-nav__item">
						<a href="" class="m-nav__link">
							
						</a>
					</li> --}}
					{{-- <li class="m-nav__separator">
						-
					</li>
					<li class="m-nav__item">
						<a href="" class="m-nav__link">
							<span class="m-nav__link-text">
								General Widgets
							</span>
						</a>
					</li> --}}
				</ul>
			</div>
			<div>
				<span class="m-subheader__daterange" id="m_dashboard_daterangepicker">
					<span class="m-subheader__daterange-label">
						<span class="m-subheader__daterange-title"></span>
						<span class="m-subheader__daterange-date m--font-brand"></span>
					</span>
					<a href="javascript:void(0)" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
						<i class="la la-angle-down"></i>
					</a>
				</span>
			</div>
		</div>
	</div>
	<!-- END: Subheader -->
	<div class="m-content">

		<div class="m-portlet__body  m-portlet__body--no-padding">
			<div class="row m-row--no-padding m-row--col-separator-xl" id="dashboard-summary">
				<div class="col-md-12 col-lg-6 col-xl-4">
					<!--begin::Total Profit-->
					<div class="m-widget24">
						<div class="m-widget24__item">
							<h4 class="m-widget24__title">
								Total Sale
							</h4>
							<br>
							<br>
							<span class="m-widget24__stats m--font-brand text-left">
								QAR {{number_format($total_sales[0]->total_sale,2)}}
							</span>
							<div class="m--space-10"></div>
							<div class="progress m-progress--sm">
								<div class="progress-bar m--bg-brand" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div>
					</div>
					<!--end::Total Profit-->
				</div>
				<div class="col-md-12 col-lg-6 col-xl-4">
					<!--begin::New Feedbacks-->
					<div class="m-widget24">
						<div class="m-widget24__item">
							<h4 class="m-widget24__title">
								New Order
							</h4>
							<br>
							<br>
							<span class="m-widget24__stats m--font-info">
								{{$total_new_order[0]->total_new_order}}
							</span>
							<div class="m--space-10"></div>
							<div class="progress m-progress--sm">
								<div class="progress-bar m--bg-info" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div>
					</div>
					<!--end::New Feedbacks-->
				</div>
				<div class="col-md-12 col-lg-6 col-xl-4">
					<!--begin::New Users-->
					<div class="m-widget24">
						<div class="m-widget24__item">
							<h4 class="m-widget24__title">
								Total Order
							</h4>
							<br>
							<br>
							<span class="m-widget24__stats m--font-success">
								{{$total_order[0]->total_order}}
							</span>
							<div class="m--space-10"></div>
							<div class="progress m-progress--sm">
								<div class="progress-bar m--bg-success" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
							</div>

						</div>
					</div>
					<!--end::New Users-->
				</div>
			</div>
		</div>
		
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('public/dashboard-assets/vendors/custom/fullcalendar/fullcalendar.bundle.js')}}" type="text/javascript"></script>
		<script src="{{asset('public/dashboard-assets/vendors/custom/dashboard.js')}}" type="text/javascript"></script>
<script>

// $(document).on('click','.ranges ul li',function(){
	
// 	$('.ranges ul li').removeClass('active');
// 	$(this).addClass('active');
// 	$('.daterangepicker').show();
// });
$(document).on('click','.applyBtn',function(){
	var data = $('.ranges ul li.active').data('range-key');
	var range;
	if(data == 'Today'){
		range = 1;
		get_dashboard_data(range);
	}else if(data == 'Yesterday'){
		range = 2;
		get_dashboard_data(range);
	}else if(data == 'Last 7 Days'){
		range = 3;
		get_dashboard_data(range);
	}else if(data == 'Last 30 Days'){
		range = 4;
		get_dashboard_data(range);
	}else if(data == 'This Month'){
		range = 5;
		get_dashboard_data(range);
	}else if(data == 'Last Month'){
		range = 6;
		get_dashboard_data(range);
	}else if(data == 'Custom Range'){
		range = 7;
		var start_date = $('input[name=daterangepicker_start]').val();
		var end_date   = $('input[name=daterangepicker_end]').val();
		get_dashboard_data(range,start_date,end_date);
	}
});
function get_dashboard_data(range,start_date='',end_date=''){
	// alert(range+'\n'+start_date+'\n'+end_date);
	if(range){
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		 $.ajax({
			url: "{{url('get-dashboard-data')}}",
			type: "POST",
			data: {range:range,start_date:start_date,end_date:end_date},
			success: function (data) {
				$('#dashboard-summary').html('');
				$('#dashboard-summary').html(data);

			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
			}
		});
	}
}
</script>
@endsection

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
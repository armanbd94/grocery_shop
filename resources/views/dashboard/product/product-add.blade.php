@extends('dashboard.master')

@section('title')
{{$page_title}}
@endsection

@section('main_content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto col-md-12">
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="{{url('/')}}" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                            <span class="m-nav__link-text">
                                Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="m-nav__item">
                        <i class="la la-angle-double-right"></i>
                        <span class="m-nav__link-text">
                            <i class="{{$page_icon}}"></i> {{$page_title}}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->    

    <!-- BEGIN: Main Content Section -->
    <div class="m-content">
        <div class="row">
            <div class="col-xl-12">
                <div class="m-portlet m-portlet--creative m-portlet--bordered-semi">
                <form method="POST" id="product_form" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed" enctype="multipart/form-data">
                    @csrf
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                    <span><i class="{{$page_icon}}"></i> {{$page_title}}</span>
                                </h2>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                    @if(in_array('Product Add',session()->get('permission')))
                                   <button type="submit" class="btn btn-primary m-btn m-btn--air m-btn--custom" id="save_product">
                                    <i class="fas fa-save"></i> Save
                                    </button>
                                    @endif
                                </li>
                                <li class="m-portlet__nav-item">
                                   <a href="{{url('product')}}" class="btn btn-danger m-btn m-btn--air m-btn--custom back-btn">
                                    <i class="fas fa-reply-all"></i> Back
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-portlet__body">
                            
                                
                                <ul class="nav nav-pills nav-pills--primary" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active show" data-toggle="tab" href="#m_tabs_5_1">
                                            General
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#m_tabs_5_2">
                                            Price Option
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#m_tabs_5_3">
                                            Additional Image
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content pt-0">
                                    <div class="tab-pane active show" id="m_tabs_5_1" role="tabpanel">
                                       <div class="form-group m-form__group row">
                                            <div class="col-lg-6 pb-15">
                                                <label for="brand_id">
                                                    Brand
                                                </label>
                                                <select class="form-control m-input chosen-select" name="brand_id">
                                                    <option value=''>Select Please</option>
                                                    @if (!empty($brands))
                                                        @foreach ($brands as $brand)
                                                            <option value='{{$brand->id}}'>{{$brand->brand_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <small class="error error_brand_id text-danger"></small>
                                            </div>
                                            <div class="col-lg-6 pb-15">
                                                <label for="category">
                                                    Category <span class="required">*</span>
                                                </label>
                                                <select class="form-control selectpicker"  name="category[]" multiple data-live-search="true">
                                                    {{-- @if(count($categories) > 0)
                                                        @foreach ($categories as $category)
                                                            <option value="{{$category->id}}">{{$category->category_name}}</option>
                                                        @endforeach
                                                    @endif --}}
                                                    {!! $categories !!}
                                                </select>
                                                <small class="error error_category text-danger"></small>  
                                            </div>
                                            <div class="col-lg-6 pb-15">
                                                <label for="product_name">
                                                    Name <span class="required">*</span>
                                                </label>
                                                <input class="form-control m-input" type="text" name="product_name">
                                                <small class="error error_product_name text-danger"></small>
                                            </div>
                                            <div class="col-lg-6 pb-15">
                                                <label for="product_variation">
                                                    Product Variation
                                                </label>
                                                <input class="form-control m-input" type="text" name="product_variation">
                                                <small class="error error_product_variation text-danger"></small>
                                            </div>
                                            <div class="col-lg-6 pb-15">
                                                <label for="weight">
                                                    Weight <span class="required">*</span>
                                                </label>
                                                <input class="form-control m-input" type="text" name="weight">
                                                <small class="error error_weight text-danger"></small>
                                            </div>
                                            <div class="col-lg-6 pb-15">
                                                <label for="unit">
                                                    Unit <span class="required">*</span>
                                                </label>
                                                <select class="form-control chosen-select" name="unit" id="unit" >
                                                    <option value="">Please Select</option>
                                                    <?php
                                                    if (!empty($units)) {
                                                        foreach ($units as $unit) {
                                                    echo '<option value="' . $unit->short_name . '">' . $unit->short_name . ' (' . $unit->full_name . ') </option>';
                                                        }
                                                    }
                                                    ?>     
                                                                                                                
                                                </select>
                                                <small class="error error_unit text-danger"></small>
                                            </div>
                                            <div class="col-lg-6 pb-15">
                                                <label for="price">
                                                    Price <span class="required">*</span>
                                                </label>
                                                <input class="form-control m-input price" type="text" name="price">
                                                <small class="error error_price text-danger"></small>
                                            </div>
                                            <div class="col-lg-6 pb-15">
                                                <label for="featured_image">
                                                    Featured Image <span class="required">*</span>
                                                </label>
                                                <input class="form-control m-input" type="file" name="featured_image">
                                                <small class="error error_featured_image text-danger"></small> 
                                            </div>
                                            <div class="col-lg-12 pb-15">
                                                <label for="description">
                                                    Description
                                                </label>
                                                <textarea class="form-control m-input col-md-7" name="description" id="description"></textarea>                                   
                                                <small class="error error_description text-danger"></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="m_tabs_5_2" role="tabpanel">
                                        <div class="form-group m-form__group row">
                                            <table class="table table-stripped table-bordered">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <label for="">Weight</label>
                                                        <input type="text" id="pweight" class="form-control">
                                                        <span class="error error_pweight text-danger"></span>
                                                    </td>
                                                    <td>
                                                        <label for="">Unit</label>
                                                        <select class="form-control chosen-select" id="punitname" >
                                                            <option value="">Please Select</option>
                                                            <?php
                                                            if (!empty($units)) {
                                                                foreach ($units as $unit) {
                                                            echo '<option value="' . $unit->short_name . '">' . $unit->short_name . ' (' . $unit->full_name . ') </option>';
                                                                }
                                                            }
                                                            ?>     
                                                                                                                        
                                                        </select>
                                                        <span class="error error_punitname text-danger"></span>
                                                    </td>
                                                    <td>
                                                        <label for="">Price</label>
                                                        <input type="text" id="pprice" class="form-control price">
                                                        <span class="error error_pprice text-danger"></span>
                                                    </td>
                                                    {{-- <td>
                                                        <label for="">Default?</label>
                                                        <select class="form-control" id="is_default" >
                                                            <option value="1">Yes</option>
                                                            <option value="2" selected>No</option>
                                                        </select>
                                                        <span class="error error_is_default text-danger"></span>
                                                    </td> --}}
                                                    <td style="padding-top:31px;"><button id="add_product_weightwise_price" type="button"class="btn btn-primary"><i class="fas fa-plus-square"></i> Add</button></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <table id="product_weightwise_price_list" class="table table-stripped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th> Weight</th>
                                                    <th> Unit</th>
                                                    <th> Price </th>
                                                    {{-- <th> Default </th> --}}
                                                    <th> Actions </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="m_tabs_5_3" role="tabpanel">
                                        <div class="form-group m-form__group row">
                                            <table id="product_additional_image_img" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th> Preview </th>
                                                        <th> Image url </th>
                                                        <th  class="text-center"> Actions </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td><img class="table-img" src="{{asset(NO_IMAGE_AVAILABLE)}}" alt="No Image Available" /></td>
                                                    <td>
                                                    <div class="form-group">
                                                        <input type="file" name="product_additional_image[0]" id="" class="file">
                                                        <div class="input-group col-xs-12"> 
                                                        <input type="text" id="Upload_Image" class="form-control input-lg" disabled placeholder="Upload Image" style="border-color: #ccc;color: #6f727d;background-color: #dae0e6;">
                                                        <span class="input-group-btn">
                                                        <button class="browse btn btn-primary input-lg" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                                        </span> </div>
                                                    </div>
                                                    
                                                    </td>
                                                    <td class="text-center"><button class="btn btn-primary add-row"><i class="fas fa-plus-square"></i></button></td>
                                                </tr>
                                                
                                                </tbody>
                                            </table>                         
                                        </div> 
                                    </div>
                                </div>
                            
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN: Main Content Section -->

</div>

@endsection

@section('script')
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>
$(document).ready(function(){
    CKEDITOR.replace('description'); //CKEDITOR INITILIZATION   

    localStorage.removeItem('product_weightwise_price_list');
    localStorage.removeItem('product_weightwise_price_row');

    //on click save button product form submit code
    $('#product_form').on('submit', function(event){
            event.preventDefault();
        CKEDITOR.instances.description.updateElement();
        // $('#product_form').submit();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url:"{{url('/product-store')}}",
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            contentType:false,
            cache:false,
            processData:false,
            beforeSend: function () {
                $('.ajax_loading').show();
            },
            complete: function() {
                $('.ajax_loading').hide();
            },
            success: function (data) {
                console.log(data);
                if (data.success) {
                    console.log(data);
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
                    toastr.success(data.success, "SUCCESS");
                    $('#product_form')[0].reset();
                    localStorage.removeItem('product_weightwise_price_list');
                    localStorage.removeItem('product_weightwise_price_row');
                    $('.chosen-select').trigger('chosen:updated');
                    location.reload();
                } else {

                    $("#product_form").find('.error').text('');
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('.form-group').find('.error_' +
                            key).text(value);
                    });
                }
                // $('.ajax_loading').hide();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding  data');
                alert(jqXHR+'<br>'+textStatus+'<br>'+errorThrown);
                // $('.ajax_loading').hide();
            }
        });
    });

    //  $(".price").TouchSpin({
    //     buttondown_class: "btn btn-secondary",
    //     buttonup_class: "btn btn-secondary",
    //     min: 0.1,
    //     max: 10000000,
    //     step: .1,
    //     decimals: 3,
    //     // boostat: 5,
    //     // maxboostedstep: 10,
    //     verticalbuttons: !0,
    //     verticalupclass: "la la-plus",
    //     verticaldownclass: "la la-minus",
    //     prefix: "Qr",
    // });

    /** BEGIN:: ON CLICK BROWSE FILE IT WILL SHOW THE PREVIEW OF FILE IN TABLE COLUMN CODE **/
    $(document).on('click', '.browse', function(){
        var file = $(this).parent().parent().parent().find('.file');
        file.trigger('click');
    });

    $(document).on('change', '.file', function(){
        $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
        var image = $(this).closest('tr').find('td img');
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                image.attr('src', e.target.result).width(100).height(100);
            };

            reader.readAsDataURL(input.files[0]);
        }
        
        
    });
    /** END:: ON CLICK BROWSE FILE IT WILL SHOW THE PREVIEW OF FILE IN TABLE COLUMN CODE **/

    /** BEGIN:: ON CLICK ADD ROW BUTTON IT WILL INCREAMENT ROW ONE BY ONE CODE **/
    var n = 1
    var j=1;
    $(document).on('click', '.add-row', function(event){
        event.preventDefault();	
        var markup = '<tr><td><img class="table-img" src="{{asset(NO_IMAGE_AVAILABLE)}}" width="50px" alt="No Image Available" /></td><td><div class="form-group"><input type="file" name="product_additional_image['+n+']" id="" class="file"><div class="input-group col-xs-12"><input type="text" id="Upload_Image" class="form-control input-lg" disabled placeholder="Upload Image"><span class="input-group-btn"><button class="browse btn btn-primary input-lg" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button></span></div></div></td><td class="text-center"><button class="btn btn-danger remove reset-btn"><i class="fas fa-trash"></i></button></td> </tr>';
        if(n < 4){
                    $("#product_additional_image_img tbody").append(markup);
        }
        n++;              
    });
    /** END:: ON CLICK ADD ROW BUTTON IT WILL ADD ROW CODE **/

    /** BEGIN:: ON CLICK REMOVE ROW BUTTON IT WILL REMOVE ROW  CODE **/
    $(document).on('click', '.remove', function(event){	
        event.preventDefault();
        $(this).closest('tr').remove();
    });
    /** END:: ON CLICK REMOVE ROW BUTTON IT WILL REMOVE ROW  CODE **/

    //STORE PRODUCT WEIGHT AND PRICE RELATED DATA INTO LOCAL STORAGE
    var mrow = localStorage.getItem('product_weightwise_price_row');
	var wrapper = $("#product_weightwise_price_list tbody"); //Fields wrapper
	var add_button = $("#add_product_weightwise_price"); //Add button ID

	if (mrow != 'null') {
		var product_weightwise_price_row = mrow;

	} else {
		localStorage.setItem('product_weightwise_price_row', '0')
		var product_weightwise_price_row = 0;
	}


	if (localStorage.getItem('product_weightwise_price_list') === null) {
		var a = [];
		a.push(JSON.parse(localStorage.getItem('product_weightwise_price_list')));
		localStorage.setItem('product_weightwise_price_list', JSON.stringify(a));
	}

	var additional_product_table = JSON.parse(localStorage.getItem('product_weightwise_price_list'));

	if (additional_product_table) {
		$(wrapper).append(additional_product_table);
	}

	$(add_button).click(function (e) {
		e.preventDefault();
		var weight     = $("#pweight").val();
		var unitname   = $("#punitname").val();
		var price      = $("#pprice").val();
		// var is_default = $("#is_default").val();
		// var is_default_text= $("#is_default option:selected").text();
		if (weight && unitname && price) {
      var html_tr = '<tr data-id="' + product_weightwise_price_row + '"><td><input type="text" class="form-control" name="weightwise_price[' + product_weightwise_price_row + '][weight]" value="' + weight + '" readonly></td><td><input type="text" class="form-control" name="weightwise_price[' + product_weightwise_price_row + '][unitname]" value="' + unitname + '" readonly></td><td><input type="text" class="form-control" name="weightwise_price[' + product_weightwise_price_row + '][price]" value="' + price + '" readonly></td><td><button class="btn btn-danger remove_field"><i class="fas fa-trash"></i> </button></td></tr>';
			$(wrapper).prepend(html_tr);
			product_weightwise_price_row++;
			var a = [];
			localStorage.setItem('product_weightwise_price_list', JSON.stringify(a));
			$("#product_weightwise_price_list tbody").each(function () {
				SaveDataToLocalStorage($(this).html());
			});
			$("#pweight").val('');
			$("#punitname").val('').trigger('chosen:updated');
			$("#pprice").val('');
			// $("#is_default").val(2);
		} else {
			if($("#pweight").val().length == 0){
                $('.error_weight').text('The weight field is required');
            }
            if($("#punitname").val().length == 0){
                $('.error_unitname').text('The unit field is required');
            }
            if($("#pprice").val().length == 0){
                $('.error_price').text('The price field is required');
            }
            // if($("#is_default").val().length == 0){
            //     $('.error_is_default').text('The default field is required');
            // }
		}
	});

	$(wrapper).on("click", ".remove_field", function (e) {
		e.preventDefault();
		$(this).closest('tr').remove();
		var a = [];
		localStorage.setItem('product_weightwise_price_list', JSON.stringify(a));
		$("#product_weightwise_price_list tbody").each(function () {
			SaveDataToLocalStorage($(this).html());
		});
		disable_dropdown();
	})

	function SaveDataToLocalStorage(data) {
		var a = [];
		a = JSON.parse(localStorage.getItem('product_weightwise_price_list'));
		a.push($.trim(data));
		localStorage.setItem('product_weightwise_price_list', JSON.stringify(a));
		localStorage.setItem('product_weightwise_price_row', product_weightwise_price_row);
	}

});

</script>
@endsection
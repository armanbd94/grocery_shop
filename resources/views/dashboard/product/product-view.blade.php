@extends('dashboard.master')

@section('title')
{{$page_title}}
@endsection

@section('main_content')
@php
$price = $weight = $unit = '';
$available_array = [];
if(!empty($data['weightwise_price'])){
    foreach ($data['weightwise_price'] as $item){
        if ($item->is_default == 1){
            $price = $item->price;
            $weight = $item->weight;
            $unit = $item->unitname;
        }
        
        if ($item->is_default == 2){               
            $weight_unit = $item->weight.$item->unitname;
            array_push($available_array,$weight_unit);
        }
    }
}
$unit_option = '';
if (!empty($data['units'])) {
    foreach ($data['units'] as $unitname) {
        $selected = '';
        if (!empty($unit)) {
            if ($unit == $unitname->short_name){
                $selected = 'selected';
            }
        }
    $unit_option .= '<option value="'.$unitname->short_name.'">'.$unitname->short_name . ' (' . $unitname->full_name.') </option>';
    }
} 
@endphp
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
                                    @if(in_array('Product Edit',session()->get('permission')))
                                   <button type="button" class="btn btn-primary m-btn m-btn--air m-btn--custom" id="edit_product">
                                    <i class="fas fa-edit"></i> Edit
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
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{asset(PRODUCT_IMAGE_PATH.$product_data->featured_image)}}" alt="{{$product_data->product_name}}" class="w-100" />
                                </div>
                                <div class="col-md-8 py-20px">
                                    <table>
                                    <tr>
                                        <td colspan="2">
                                        <h4>
                                        @if (!empty($product_data->brand_name))
                                        {{$product_data->brand_name.' '}}
                                        @endif
                                        @if (!empty($product_data->product_variation))
                                        {{$product_data->product_variation.' '}}
                                        @endif
                                        {{$product_data->product_name}}
                                        @if(!empty($weight) && !empty($unit))
                                            {{' '.$weight.$unit}}
                                        @endif
                                        </h4>
                                        </td>
                                    </tr>

                                    @if (!empty($product_data->brand_name))
                                    <tr>
                                    <td><b>Brand</b></td><td>: {{$product_data->brand_name}}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                    <td><b>Item</b></td><td>: {{$product_data->product_name}}</td>
                                    </tr>
                                    @if (!empty($product_data->product_variation))
                                    <tr>
                                    <td><b>Variation</b></td><td>: {{$product_data->product_variation}}</td>
                                    </tr>
                                     @endif

                                    @if(!empty($price))

                                    <tr>
                                    <td><b>Featured Price</b></td><td>: QAR {{number_format($price,2)}}</td>
                                    </tr>
                                    @endif

                                    @if(!empty($weight) && !empty($unit))
                                    <tr>
                                    <td><b>Featured Content</b></td><td>: {{$weight.$unit}}</td>
                                    </tr>
                                    @endif
                                    @if(!empty($available_array) && count($available_array) > 0)
                                        <tr>
                                            <td><b>Also Available in</b></td><td>: {{implode(', ',$available_array)}}</td>
                                        </tr>
                                    @endif
                                    </table>
                                </div>
                            </div>
                                
                            <ul class="nav nav-pills nav-pills--primary pt-50px" role="tablist">
                                @if (!empty($product_data->description))
                                    <li class="nav-item">
                                        <a class="nav-link active show" data-toggle="tab" href="#m_tabs_5_1">
                                            Description
                                        </a>
                                    </li>
                                @endif
                               
                                <li class="nav-item">
                                    <a class="nav-link  @if (empty($product_data->description)) active show  @endif" data-toggle="tab" href="#m_tabs_5_2">
                                        Price Option
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#m_tabs_5_3" onclick="get_image_list()">
                                        Additional Image
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content pt-0">
                                @if (!empty($product_data->description))
                                    <div class="tab-pane active show description" id="m_tabs_5_1" role="tabpanel">
                                        {!! $product_data->description  !!}
                                    </div>
                                @endif
                                <div class="tab-pane @if (empty($product_data->description)) active show  @endif" id="m_tabs_5_2" role="tabpanel">
                                    @if(in_array('Product Add',session()->get('permission')))
                                    <form method="POST" id="add_price_form">
                                        @csrf 
                                        <input type="hidden" name="product_id" value="{{$product_data->id}}" />
                                        <table class="table table-stripped table-bordered">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <label for="">Weight</label>
                                                        <input type="text" name="weight" id="pweight" class="form-control">
                                                        <span class="error error_weight text-danger"></span>
                                                    </td>
                                                    <td>
                                                        <label for="">Unit</label>
                                                        <select class="form-control chosen-select" name="unitname" id="unitname" >
                                                            <option value="">Please Select</option>
                                                            @if (!empty($data['units'])) 
                                                                @foreach ($data['units'] as $unitname) 
                                                            <option value="{{$unitname->short_name}}">{{$unitname->short_name . ' (' . $unitname->full_name.')'}} </option>;
                                                                @endforeach
                                                            @endif     
                                                                                                                        
                                                        </select>
                                                        <span class="error error_unitname text-danger"></span>
                                                    </td>
                                                    <td>
                                                        <label for="">Price</label>
                                                        <input type="text" name="price" id="price" class="form-control price">
                                                        <span class="error error_price text-danger"></span>
                                                    </td>
                                                    <td style="padding-top:39px;"><button id="add_product_weightwise_price" type="button" class="btn btn-primary"><i class="fas fa-plus-square"></i> Add</button></td>
                                                </tr>
                                                </tbody>
                                            </table>

                                    </form>
                                    @endif

                                    <form method="post" id="update_price_form">
                                        <input type="hidden" name="product_id" value="{{$product_data->id}}" />
                                        @if(in_array('Product Edit',session()->get('permission')))
                                        <div class="pull-left py-20px">
                                        <button type="submit" class="btn btn-primary"  id="multiple_update">Update Checked Data</button>
                                        </div>
                                        @endif

                                        <table class="table table-bordered table-hover" id="price_table">
                                            <thead class="text-center">
                                                @if(in_array('Product Edit',session()->get('permission')))
                                                <td></td>
                                                @endif
                                                <td>Sr</td>
                                                <td>Weight</td>
                                                <td>Unit</td>
                                                <td>Price</td>
                                                @if(in_array('Product Delete',session()->get('permission')))
                                                <td>Action</td>
                                                @endif
                                            </thead>
                                            <tbody class="text-center">
                                                {!! $product_variation !!}
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                                <div class="tab-pane" id="m_tabs_5_3" role="tabpanel">
                                    @if(in_array('Product Add',session()->get('permission')))
                                    <form id="image_upload_form" method="post" action="{{url('upload-product-image')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class=" row">
                                            <input type="hidden" name="product_id" value="{{$product_data->id}}"/>
                                            <div class="col-md-3 text-right"><h5>Select Images</h5></div>
                                            <div class="form-group col-md-6">
                                                <input type="file" class="form-control" name="product_image[]" id="product_image" accept="image/*" multiple />
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-success">Upload</button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="progress  image-progress">
                                        <div class="progress-bar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                            0%
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="row  py-20px product_images"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN: Main Content Section -->

    <!-- BEGIN:: ADD AND EDIT MODAL FORM -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <form method="POST" id="productUpdateForm">
                    <div class="modal-body">
                        <div class="form-group m-form__group row">
                            @csrf
                            <input type="hidden" name="id" value="{{$product_data->id}}" />
                            <div class="col-lg-6 pb-15">
                                <label for="brand_id">
                                    Brand
                                </label>
                                <select class="form-control m-input chosen-select" name="brand_id">
                                    <option value=''>Select Please</option>
                                    @if (!empty($data['brands']))
                                        @foreach ($data['brands'] as $brand)
                                            <option 
                                            @if (!empty($product_data->brand_id))
                                                @if ($product_data->brand_id == $brand->id)
                                                    selected
                                                @endif
                                            @endif
                                            value='{{$brand->id}}'>{{$brand->brand_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <small class="error error_brand_id text-danger"></small>
                            </div>
                            <div class="col-lg-6 pb-15">
                                <label for="category">
                                    Category <span class="required">*</span>
                                </label>
                                <select class="form-control m-select2  chosen-select"  name="category[]" multiple="multiple">
                                    @if(count($data['categories']) > 0)
                                        @foreach ($data['categories'] as $category)
                                            <option 
                                            @if(in_array($category->id,$data['product_category']))
                                            selected
                                            @endif
                                            value="{{$category->id}}">{{$category->category_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <small class="error error_category text-danger"></small>  
                            </div>
                            <div class="col-lg-6 pb-15">
                                <label for="product_name">
                                    Name <span class="required">*</span>
                                </label>
                                <input class="form-control m-input" type="text" name="product_name" value="{{$product_data->product_name}}">
                                <small class="error error_product_name text-danger"></small>
                            </div>
                            <div class="col-lg-6 pb-15">
                                <label for="product_variation">
                                    Product Variation
                                </label>
                                <input class="form-control m-input" type="text" name="product_variation" value="{{$product_data->product_variation}}">
                                <small class="error error_product_variation text-danger"></small>
                            </div>
                            <div class="col-lg-4 pb-15">
                                <label for="weight">
                                    Weight <span class="required">*</span>
                                </label>
                                <input class="form-control m-input" type="text" name="weight" value="{{$weight}}">
                                <small class="error error_weight text-danger"></small>
                            </div>
                            <div class="col-lg-4 pb-15">
                                <label for="unit">
                                    Unit <span class="required">*</span>
                                </label>
                                <select class="form-control chosen-select" name="unit" id="unit" >
                                    <option value="">Please Select</option>
                                    @if (!empty($data['units'])) 
                                        @foreach ($data['units'] as $unitname) 
                                    <option
                                     @if (!empty($unit)) 
                                        @if ($unit == $unitname->short_name)
                                            {{'selected'}}
                                        @endif
                                     @endif
                                     value="{{$unitname->short_name}}">{{$unitname->short_name . ' (' . $unitname->full_name.')'}} </option>;
                                        @endforeach
                                    @endif   
                                                                                                
                                </select>
                                <small class="error error_unit text-danger"></small>
                            </div>
                            <div class="col-lg-4 pb-15">
                                <label for="price">
                                    Price <span class="required">*</span>
                                </label>
                                <div class="input-group m-input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            QAR
                                        </span>
                                    </div>
                                    <input class="form-control m-input price" type="text" name="price" value="{{number_format($price,2)}}">
                                </div>
                                
                                <small class="error error_price text-danger"></small>
                            </div>
                            <div class="col-lg-10 pb-15">
                                <label for="featured_image">
                                    Featured Image <span class="required">*</span>
                                </label>
                                <input class="form-control m-input" type="file" name="featured_image">
                                <small class="error error_featured_image text-danger"></small> 
                            </div>
                            <div class="col-lg-2 pb-15">
                                <img src="{{asset(PRODUCT_IMAGE_PATH.$product_data->featured_image)}}" alt="{{$product_data->product_name}}" class="w-100" style="border: 1px solid #ccc;" />
                            </div>
                            <div class="col-lg-12 pb-15">
                                <label for="description">
                                    Description
                                </label>
                                <textarea class="form-control m-input col-md-7" name="description" id="description">{!! $product_data->description !!}</textarea>                                   
                                <small class="error error_description text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-close" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary " id="update-btn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END:: ADD AND EDIT MODAL FORM -->

</div>

@endsection

@section('script')
<script src="{{asset('public/website-assets/plugins/lazysizes.min.js')}}"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>
$(document).ready(function(){
    CKEDITOR.replace('description'); //CKEDITOR INITILIZATION   
    // id = '{{$product_data->id}}';
    // fetch_price_list(id);
    //BEGIN: ON CLICK ADD BUTTON & SHOW ADD MODAL CODE
    $('#edit_product').click(function () {

        $('#productUpdateForm')[0].reset();
        $(".error").each(function () {
            $(this).empty();
        });

        $('#productModal').modal({
            keyboard: false,
            backdrop: 'static'
        })
        $('.modal-title').html('<i class="fas fa-plus-square"></i> <span>Edit Product Details</span>');
    });
    //END: ON CLICK ADD BUTTON & SHOW ADD MODAL CODE

    //on click save button product form submit code
    $('#productUpdateForm').on('submit', function(event){
        event.preventDefault();
        CKEDITOR.instances.description.updateElement();
        // $('#product_form').submit();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url:"{{url('/product-update')}}",
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            contentType:false,
            cache:false,
            processData:false,
            beforeSend: function () {
                $('#update-btn').addClass('m-loader m-loader--light m-loader--left');
            },
            complete: function() {
                $('#update-btn').removeClass('m-loader m-loader--light m-loader--left');
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
                    location.reload();
                } else {

                    $("#productUpdateForm").find('.error').text('');
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('#productUpdateForm .form-group').find('.error_' +
                            key).text(value);
                    });
                }
                // $('.ajax_loading').hide();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error updating data');
                console.log(jqXHR+'<br>'+textStatus+'<br>'+errorThrown);
                // $('.ajax_loading').hide();
            }
        });
    });


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

    $(document).on('click', '.check_box', function(){
        var html = '';
        if(this.checked)
        {
            <?php 
            if(in_array('Product Edit',session()->get('permission'))){ ?>
            html = '<td><input type="checkbox" id="'+$(this).attr('id')+'" data-sr="'+$(this).data('sr')+'" data-weight="'+$(this).data('weight')+'" data-unitname="'+$(this).data('unitname')+'" data-price="'+$(this).data('price')+'" data-delete="'+$(this).data('delete')+'" class="check_box" checked /></td>';
            <?php } ?>
            html += '<td>'+$(this).data('sr')+'</td>';
            html += '<td><input type="text" name="weight[]" class="form-control" value="'+$(this).data("weight")+'" /></td>';
            html += '<td><select name="unitname[]" id="unitname_'+$(this).attr('id')+'" class="form-control"><?php echo $unit_option; ?></select></td>';
            html += '<td><input type="text" name="price[]" class="form-control" value="'+$(this).data("price")+'" /></td>';
            <?php 
            if(in_array('Product Delete',session()->get('permission'))){ ?>
            html += '<td><button type="button" data-id="'+$(this).data("delete")+'" class="btn btn-danger reset-btn btn-sm price_delete_btn"><i class="fas fa-trash"></i></button><input type="hidden" name="hidden_id[]" value="'+$(this).attr('id')+'" /></td>';        
            <?php } ?>
        }
        else
        {
            <?php 
            if(in_array('Product Edit',session()->get('permission'))){ ?>
            html = '<td><input type="checkbox" id="'+$(this).attr('id')+'" data-sr="'+$(this).data('sr')+'" data-weight="'+$(this).data('weight')+'" data-unitname="'+$(this).data('unitname')+'" data-price="'+$(this).data('price')+'" data-delete="'+$(this).data('delete')+'" class="check_box" /></td>';
            <?php } ?>
            html += '<td>'+$(this).data('sr')+'</td>';
            html += '<td>'+$(this).data('weight')+'</td>';
            html += '<td>'+$(this).data('unitname')+'</td>';
            html += '<td>'+$(this).data('price')+'</td>';        
            <?php 
            if(in_array('Product Delete',session()->get('permission'))){ ?>
            html += '<td><button type="button" data-id="'+$(this).data("delete")+'" class="btn btn-danger reset-btn btn-sm price_delete_btn"><i class="fas fa-trash"></i></button></td>';        
            <?php } ?>
        }
        $(this).closest('tr').html(html);
        $('#unitname_'+$(this).attr('id')+'').val($(this).data('unitname'));
    });

    $('#add_product_weightwise_price').on('click',function(){
        $.ajax({
                url:"{{url('store-product-price')}}",
                method:"POST",
                data:$('#add_price_form').serialize(),
                beforeSend: function () {
                    $('#add_product_weightwise_price').addClass('m-loader m-loader--light m-loader--left');
                },
                complete: function() {
                    $('#add_product_weightwise_price').removeClass('m-loader m-loader--light m-loader--left');
                },
                success:function(data)
                {
                    if(data.success){
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
                        $("#add_price_form")[0].reset();
                        $("#add_price_form #unitname").val('').trigger('chosen:updated');
                        $("#add_price_form table").find('.error').text('');
                        get_price_list();
                    }else {

                        $("#add_price_form table").find('.error').text('');
                        console.log(data.errors);
                        $.each(data.errors, function (key, value) {
                            $('#add_price_form table').find('.error_'+key).text(value);
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error in save data');
                    console.log(jqXHR+'<br>'+textStatus+'<br>'+errorThrown);
                }
            })
    });

    $('#update_price_form').on('submit', function(event){
        event.preventDefault();
        if($('.check_box:checked').length > 0)
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{url('update-price-data')}}",
                method:"POST",
                data:$(this).serialize(),
                beforeSend: function () {
                    $('#multiple_update').addClass('m-loader m-loader--light m-loader--left');
                },
                complete: function() {
                    $('#multiple_update').removeClass('m-loader m-loader--light m-loader--left');
                },
                success:function(data)
                {
                    if(data.success){
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
                        get_price_list();
                    }
                    
                    
                }
            })
        }
    });

    $(document).on('click','.price_delete_btn',function(){
        var id = $(this).data('id');
        
        swal({
            title: 'Are you sure?',
            text: "It will be deleted permanently!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'btn btn-success',
            cancelButtonColor: 'btn btn-danger',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,

            preConfirm: function () {
                return new Promise(function (resolve) {
                    var _token = "{{csrf_token()}}";
                    $.ajax({
                            url: '{{ url("delete-product-price") }}',
                            type: 'POST',
                            data:{id:id,_token:_token},
                            dataType: 'json'
                        })
                        .done(function (response) {
                            if (response.status ==
                                'success') {
                                swal({
                                    title: "Deleted!",
                                    text: response.message,
                                    type: "success"
                                }).then(function () {
                                    get_price_list();
                                });

                            } else if (response.status == 'error') {
                                swal('Error deleting!', response.message,'error');
                            }


                        })
                        .fail(function () {
                            swal('Oops...',
                                'Something went wrong with ajax !',
                                'error');
                        });
                });
            },
            allowOutsideClick: false
        });
    });

    $('#image_upload_form').ajaxForm({
        beforeSend:function(){
            $('.progress-bar').text('0%');
            $('.progress-bar').css('width', '0%');
            $('.image-progress').show();
        },
        uploadProgress:function(event, position, total, percentComplete){

            $('.progress-bar').css('background-color', '#024883');
            $('.progress-bar').text(percentComplete + '0%');
            $('.progress-bar').css('width', percentComplete + '0%');

        },
        success:function(data)
        {
            
            if(data.success)
            {
                $('.progress-bar').css('background-color','#34bfa3');
                $('.progress-bar').text('Uploaded');
                $('.progress-bar').css('width', '100%');
                $('#image_upload_form')[0].reset();
                toastr.success(data.success, "SUCCESS");
                get_image_list();
                setInterval(function(){
                    $('.progress-bar').text('0%');
                    $('.progress-bar').css('width', '0%');
                    $('.progress-bar').css('background-color', '#e9ecef');
                    $('.image-progress').hide();
                },5000);
                
            }else if(data.error){
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
                toastr.error(data.error, "ERROR");
                $('.progress-bar').css('background-color','red');
                $('.progress-bar').text('Error');
                $('.progress-bar').css('width', '100%');
                setInterval(function(){
                    $('.progress-bar').text('0%');
                    $('.progress-bar').css('width', '0%');
                    $('.progress-bar').css('background-color','#e9ecef');
                    $('.image-progress').hide();
                },5000);
            }
        }
    });

    $(document).on('click','.delete-img-btn',function(){
        var id = $(this).data('id');
        
        swal({
            title: 'Are you sure?',
            text: "It will be deleted permanently!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'btn btn-success',
            cancelButtonColor: 'btn btn-danger',
            confirmButtonText: 'Yes, delete it!',
            showLoaderOnConfirm: true,

            preConfirm: function () {
                return new Promise(function (resolve) {
                    var _token = "{{csrf_token()}}";
                    $.ajax({
                            url: '{{ url("delete-product-image") }}',
                            type: 'POST',
                            data:{id:id,_token:_token},
                            dataType: 'json'
                        })
                        .done(function (response) {
                            if (response.status ==
                                'success') {
                                swal({
                                    title: "Deleted!",
                                    text: response.message,
                                    type: "success"
                                }).then(function () {
                                    get_image_list();
                                });

                            } else if (response.status == 'error') {
                                swal('Error deleting!', response.message,'error');
                            }


                        })
                        .fail(function () {
                            swal('Oops...',
                                'Something went wrong with ajax !',
                                'error');
                        });
                });
            },
            allowOutsideClick: false
        });
    });

});
function get_price_list(){
    var product_id = '{{$product_data->id}}';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url:"{{url('product-price-list')}}",
        method:"POST",
        data:{product_id:product_id},
        success:function(data)
        {
            $('#price_table tbody').html('');
            $('#price_table tbody').html(data);
        }
    })
}
function get_image_list(){
    var product_id = '{{$product_data->id}}';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url:"{{url('product-image-list')}}",
        method:"POST",
        data:{product_id:product_id},
        success:function(data)
        {
            $('.product_images').html('');
            $('.product_images').html(data);   
        }
    })
}
</script>
@endsection
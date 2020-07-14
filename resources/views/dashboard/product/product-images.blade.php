
@if (!empty($data) && count($data) > 0)
    @foreach ($data as $img)
        <div class="col-lg-4 text-center mb-25">
            <div class="col-lg-12 pt-20 pb-20 shadow-effect" style="">
                <div class="house-product-img-box">
                    <img class="lazyload" src="{{asset(LOADING_ICON)}}" data-src="{{asset(PRODUCT_IMAGE_PATH.$img->image)}}" alt="Product Image">
                </div>
                @if(in_array('Product Delete',session()->get('permission')))
                <button type="button" data-id="{{$img->id}}" class="btn btn-primary btn-sm delete-img-btn" data-toggle="tooltip" title="Remove Image"><i class="fas fa-times"></i></button>
                @endif
            </div>  
        </div>
    @endforeach
@else 
<div class="col-md-12  text-center mb-25"><h4 class="text-danger">No Image Uploaded</h4></div>
@endif
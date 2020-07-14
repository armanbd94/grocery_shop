@extends('dashboard.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('main_content')
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN:: SUBHEADER SECTION -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto col-md-12">
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="{{url('/')}}" class="m-nav__link m-nav__link--cover_image">
                            <i class="m-nav__link-cover_image la la-home"></i>
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
    <!-- END:: SUBHEADER SECTION -->

    <!-- BEGIN:: MAIN CONTENT SECTION -->
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
                                     @if(in_array('Baner Add',session()->get('permission')))
                                    <button type="button" class="btn add-btn" id="add_data">
                                        <i class="fas fa-plus-square"></i>
                                        Add New
                                    </button>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="button" class="btn add-btn pull-right add_data" >
                                    <i class="far fa-image"></i>
                                    Add New Image
                                </button>
                            </div>
                            <div class="col-lg-12 pt-20 slider_image">
                                <!-- START:: SHOW LOADING IMAGE ON LOAD BODY -->
                                <div class="content-loader">
                                    <div id="loading"><img src="{{asset('public/dashboard-assets/img/loading.svg')}}" alt="loading..." /></div>
                                </div>
                                <!-- END:: SHOW LOADING IMAGE ON LOAD BODY -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END:: MAIN CONTENT SECTION -->

    <!-- BEGIN:: ADD IMAGE MODAL FORM -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <form method="POST" enctype="multipart/form-data" id="imageForm">
                    <div class="modal-body">
                       {{ csrf_field() }}
                        <div class="form-group m-form__group row">
                            <table id="slide_img" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> Preview </th>
                                        <th> Image url </th>
                                        <th> Title </th>
                                        <th> Link </th>
                                        <th> Sequence </th>
                                        <th> Actions </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><img class="table-img" src="{{asset(UPLOAD_PATH.'/no-image-available.png')}}" alt="Project Document" /></td>
                                    <td>
                                    <div class="form-group">
                                        <input type="file" name="slider_image[0]" id="" class="file">
                                        <div class="input-group col-xs-12"> 
                                        <input type="text" id="Upload_Image" class="form-control input-lg" disabled placeholder="Upload Image" style="border-color: #ccc;color: #6f727d;background-color: #dae0e6;">
                                        <span class="input-group-btn">
                                        <button class="browse btn btn-primary input-lg" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
                                        </span> </div>
                                    </div>
                                    
                                    </td>
                                    <td><input  class="form-control" id="title" name="title[]" placeholder="Write slide title (optional)"/></td>
                                    <td><input  class="form-control" id="link" name="link[]" placeholder="Write slide link (optional)"/></td>
                                    <td><input  class="form-control" id="sequence" name="sequence[]" placeholder="Write slider sequence" required/></td>
                                    <td><button class="btn btn-primary add-row"><i class="fas fa-plus-square"></i></button></td>
                                </tr>
                                
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td class="text-center"><small class="error error_slider_image text-danger"></small></td>
                                        <td class="text-center"><small class="error error_title text-danger"></small></td>
                                        <td class="text-center"><small class="error error_link text-danger"></small></td>
                                        <td class="text-center"><small class="error error_sequence text-danger"></small></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>                        
                        </div>                                                
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-close" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary" id="submit-all">Upload</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END:: ADD IMAGE MODAL FORM -->

</div>



<script src="{{asset('public/website-assets/plugins/lazysizes.min.js')}}"></script>
<script>
 /************************************************
========== :: Website Slide Image :: ============
************************************************/
get_slide_image();
$('.content-loader').hide();
/** BEGIN:: IMAGE ADD MODAL SHOW CODE **/
    $('.add_data').click(function () {

        $('#imageForm')[0].reset();  
        // $("#slide_img").find("tr:gt(1)").remove();     
        $(".error").each(function () {
            $(this).empty();
        });
        $('#imageModal').modal({
            keyboard: false,
            backdrop: 'static'
        })
        $('.modal-title').html('<i class="fas fa-plus-square"></i> <span>Add Slide Image</span>');

    });
    /** END:: IMAGE ADD MODAL SHOW CODE **/

    /** BEGIN:: WEBSITE SLIDER IMAGE ADD AJAX CODE **/
    $('#imageForm').on('submit', function(event){
        event.preventDefault();
        $.ajax({
            url:"{{url('/banner')}}",
            type: "POST",
            data: new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            beforeSend: function () {
                $('.ajax_loading').show();
            },
            success: function (data)
            {
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
                if (data.success) {

                    toastr.success(data.success,"SUCCESS");
                    $('#imageForm')[0].reset();
                    $('#imageModal').modal('hide');
                    get_slide_image();
                    $('.ajax_loading').hide();

                } else if(data.error){
                    toastr.error(data.error,"ERROR");
                    get_slide_image();
                    $('.ajax_loading').hide();
                }else
                {
                
                    $("#imageForm").find('.error').text(''); 
                    console.log(data.errors);
                    $.each(data.errors, function (key, value) {
                        $('#imageForm').find('.error_' + key).text(value);
                    });
                    $('.ajax_loading').hide();

                }


            },
            error: function (jqXHR, textStatus, errorThrown)
            {
            alert('Error update data');
            $('.ajax_loading').hide();
            }
        });
    });
    /** END:: WEBSITE SLIDER IMAGE ADD AJAX CODE **/
    

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
        var markup = '<tr><td><img class="table-img" src="{{asset(UPLOAD_PATH)}}/no-image-available.png" width="50px" height="50px" alt="Slide Image" /></td><td><div class="form-group"><input type="file" name="slider_image['+n+']" id="" class="file"><div class="input-group col-xs-12"><input type="text" id="Upload_Image" class="form-control input-lg" disabled placeholder="Upload Image"><span class="input-group-btn"><button class="browse btn btn-primary input-lg" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button></span></div></div></td><td><input  class="form-control" id="title" name="title[]"  placeholder="Write slide title (optional)"/></td><td><input  class="form-control" id="link" name="link[]" placeholder="Write slide link (optional)"/></td><td><input  class="form-control" id="sequence" name="sequence[]" placeholder="Write slide sequence (optional)"/></td><td><button class="btn btn-danger remove reset-btn"><i class="fas fa-trash"></i></button></td> </tr>';
        if(n < 4){
            $("#slide_img tbody").append(markup);
        }
        n++;              
    });
    /** END:: ON CLICK ADD ROW BUTTON IT WILL ADD ROW CODE **/

    /** BEGIN:: ON CLICK REMOVE ROW BUTTON IT WILL REMOVE ROW  CODE **/
    $(document).on('click', '.remove', function(event){	
        event.preventDefault();
        $(this).closest('tr').remove();
    });
    /************************************************
    ========== :: /Website Slide Image :: ============
    ************************************************/

    /***************************************************************
Begin :: Website Slide Image delete and get images ajax function
****************************************************************/
function delete_item(id){
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
                        url: '{{ url("/banner-delete") }}',
                        type: 'POST',
                        data:{id:id,_token:_token},
                        dataType: 'json'
                    })
                    .done(function (response) {
                        if (response.status ==
                            'success') {
                            swal({
                                title: "Deleted!",
                                text: response
                                    .message,
                                type: "success"
                            }).then(function () {
                                get_slide_image();
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
}
function get_slide_image() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: "{{url('/banner-list')}}",
        type: "POST",
        beforeSend: function () {
            $('.content-loader').show();
        },
        success: function (data) {
            if (data) {
                console.log(data);

                $(".slider_image").html('');   
                $(".slider_image").html(data);   
                $('[data-toggle="tooltip"]').tooltip();                
                $('.content-loader').hide();
            } else {
                $('.content-loader').hide();
            }


        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
        }
    });
}
/***************************************************************
End :: Website Slide Image delete and get images ajax function
****************************************************************/
</script>

@endsection

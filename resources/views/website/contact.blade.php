@extends('website.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('stylesheet')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />    
@endsection

@section('main_content')
<section class="section-padding bg-dark inner-header">
    <div class="container">
       <div class="row">
          <div class="col-md-12 text-center">
             <h1 class="mt-0 mb-3 text-white">{{ucwords($page_title)}}</h1>
             <div class="breadcrumbs">
                <p class="mb-0 text-white"><a class="text-white" href="{{url('/')}}">Home</a>  /  <span class="text-success">{{ucwords($page_title)}}</span></p>
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- End Inner Header -->
 <!-- Contact Us -->
 <section class="section-padding">
    <div class="container">
       <div class="row">
          <div class="col-lg-4 col-md-4">
             <h3 class="mt-1 mb-5">Get In Touch</h3>
             <h6 class="text-dark"><i class="mdi mdi-home-map-marker"></i> Address :</h6>
             <p>{{Helper::get_site_data()->contact_address}}</p>
             <h6 class="text-dark"><i class="mdi mdi-phone"></i> Phone :</h6>
             <p>{{Helper::get_site_data()->contact_no}}</p>
             <h6 class="text-dark"><i class="mdi mdi-email"></i> Email :</h6>
             <p>{{Helper::get_site_data()->contact_email}}</p>
             <div class="footer-social"><span>Follow : </span>
                <a href="{{json_decode(Helper::get_site_data()->social_media)->facebook}}"><i class="mdi mdi-facebook"></i></a>
                <a href="{{json_decode(Helper::get_site_data()->social_media)->twitter}}"><i class="mdi mdi-twitter"></i></a>
                <a href="{{json_decode(Helper::get_site_data()->social_media)->instagram}}"><i class="mdi mdi-instagram"></i></a>
             </div>
          </div>
          <div class="col-lg-8 col-md-8">
             <div class="card">
                <div class="card-body">
                   <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d109552.19658195564!2d75.78663251672796!3d30.900473637371658!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391a837462345a7d%3A0x681102348ec60610!2sLudhiana%2C+Punjab!5e0!3m2!1sen!2sin!4v1530462134939" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
             </div>
          </div>
       </div>
    </div>
 </section>
 <!-- End Contact Us -->
 <!-- Contact Me -->
 <section class="section-padding  bg-white">
    <div class="container">
       <div class="row">
          <div class="col-lg-12 col-md-12 section-title text-left mb-4">
             <h2>Contact Us</h2>
          </div>
          <form class="col-lg-12 col-md-12" method="post" id="contact-form">
             <div class="control-group form-group">
                <div class="controls">
                   <label>Full Name <span class="text-danger">*</span></label>
                   <input type="text" placeholder="Full Name" class="form-control" name="name" id="name" >
                   <span class="error error_name text-danger"></span>
                </div>
             </div>
             <div class="row">
               
                <div class="control-group form-group col-md-6">
                   <div class="controls">
                      <label>Email Address <span class="text-danger">*</span></label>
                      <input type="email" placeholder="Email Address"  class="form-control" name="email" id="email" >
                      <span class="error error_email text-danger"></span>
                   </div>
                </div>
                <div class="control-group form-group col-md-6">
                    <label>Subject <span class="text-danger">*</span></label>
                    <div class="controls">
                       <input type="text" placeholder="Subject" class="form-control" name="subject" id="subject" >
                       <span class="error error_subject text-danger"></span>
                    </div>
                 </div>
             </div>
             <div class="control-group form-group">
                <div class="controls">
                   <label>Message <span class="text-danger">*</span></label>
                   <textarea rows="4" cols="100" placeholder="Message"  class="form-control" name="message" id="message"  maxlength="999" style="resize:none"></textarea>
                   <span class="error error_message text-danger"></span>
                </div>
             </div>
             <div id="success"></div>
             <!-- For success/fail messages -->
             <button type="submit" class="btn btn-success">Send Message</button>
          </form>
       </div>
    </div>
 </section>
 <!-- End Contact Me -->
@endsection

@section('script')
<!--begin::Toastr Resources -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!--end::Toastr Resources -->
<script>
$(document).ready(function(){
    $('#contact-form').on('submit', function(event){
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url:"{{url('/store-contact-message')}}",
            type: "POST",
            data: $('#contact-form').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.success) {
                    
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": false,
                        "positionClass": "toast-bottom-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "500",
                        "timeOut": "2000",
                        "extendedTimeOut": "500",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "slideDown",
                        "hideMethod": "slideUp"
                    };
                    
                    $("#contact-form").find('.error').text(''); 
                    toastr.success(data.success);
                    $("#contact-form")[0].reset();
                } else
                {
                
                    $("#contact-form").find('.error').text(''); 
                    $.each(data.errors, function (key, value) {
                        $('#contact-form .form-group').find('.error_'+key).text(value);
                    });

                }


            },
            error: function (jqXHR, textStatus, errorThrown)
            {
            alert('Error send message');
            }
        });
    });
});
//update cart product function
function update_cart(rowId,qty){

   var _token = "{{csrf_token()}}";
   var url = '{{url("/update-cart")}}';
   $.ajax({
      url: url,
      type: "POST",
      data: { rowId:rowId, qty:qty, _token:_token },
      dataType: "JSON",
      success: function (data) {
         sidebar_cart();
      },
      error: function (jqXHR, textStatus, errorThrown) {
         console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
      }
   });
}

//Remove product from cart function
function remove_product_from_cart(rowId) {

   var _token = "{{csrf_token()}}";
   var url = '{{url("/remove-cart")}}';
   $.ajax({
      url: url,
      type: "POST",
      data: { rowId: rowId, _token: _token },
      dataType: "JSON",
      success: function (data) {
         total_cart_item();
         sidebar_cart();
      },
      error: function (jqXHR, textStatus, errorThrown) {
         console.log(jqXHR + '<br>' + textStatus + '<br>' + errorThrown);
      }
   });
}
</script>
@endsection
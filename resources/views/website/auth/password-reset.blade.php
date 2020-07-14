@extends('website.master') 
@section('title') 
{{$page_title}}
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


<section class="shop-list section-padding pt-20">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 py-20px" id="fpass">
                <div class="col-md-auto" id="form-section">
                    <form action="{{url('/account/reset-password')}}" method="post" id="reset_form" >
                        @csrf
                        <input type="hidden" name="token" value="{{$token}}" >
                        <div class="form-group row">
                            <label for="email" class=" font-weight-bold">Email</label>
                            <input type="email" class="form-control g-bg-white" name="email" id="email">
                            @if ($errors->has('email'))
                                <span class="text-danger">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                            <span class="error error_email text-danger"></span>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="font-weight-bold">Password</label>
                            <input type="password" class="form-control" name="password" id="password" >
                             @if ($errors->has('password'))
                                <span class="text-danger">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                             <span class="error error_password text-danger"></span>                 
                        </div>
                        <div class="form-group row">
                            <label for="password_confirmation" class=" font-weight-bold">Confirm Password</label>
                            <input type="password" class="form-control " name="password_confirmation" id="password_confirmation" >
                            
                        </div>
                        <div class="form-group row">
                            <button type="submit" class="btn btn-secondary col-md-12 button"> <b>Reset Password</b></button>                       
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Carrer Section -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />  
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>
<script>
$('#reset_form').ajaxForm({
    success:function(data)
    {
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
        if(data.success){
            toastr.success(data.success);
            setInterval(function(){
                window.location = "{{url('account-login')}}";
            }, 1500);
            
        }else if(data.errors){
            $("#reset_form").find('.error').text(''); 
                console.log(data.errors);
                $.each(data.errors, function (key, value) {
                    $('#reset_form .form-group').find('.error_' +
                        key).text(value);
                });
        }else if(data.error){
            toastr.error(data.error);
        }
    }
});
</script>
@endsection
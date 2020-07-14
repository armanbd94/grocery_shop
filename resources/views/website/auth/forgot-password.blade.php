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
                <div class="m-auto">
                    @if (session('status'))
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i> 
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }}
                    </div>
                    @endif
                    @if (session('warning'))
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-circle"></i> 
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('warning') }}
                    </div>
                    @endif
                    <p  class="text-center">Enter your email to reset your password: </p>
                   
                    <form method="post" id="send-link" action="{{url('account/send-recover-link')}}">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <input type="email" class="form-control g-bg-white {{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" placeholder="Email">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif

                        </div>
                        <div class="form-group row">
                            <button type="submit" class="btn btn-secondary col-md-12 button"> <b>Send Recovery Link</b></button>
                        </div>
                        
                    </form> 
                </div>
            </div>   
        </div>  
    </div>
</section>
<!-- End Carrer Section -->


@endsection
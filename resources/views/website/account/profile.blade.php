@extends('website.master')

@section('title')
{{ucwords($page_title)}}
@endsection

@section('main_content')
<section class="pt-3 pb-3 page-info section-padding border-bottom bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {!! $breadcrumb_menu !!} <span class="mdi mdi-chevron-right"></span><a href="">{{$page_title}}</a>
            </div>
        </div>
    </div>
</section>

<section class="account-page section-padding">
    <div class="container">
       <div class="row">
          <div class="col-lg-12 mx-auto">
             <div class="row no-gutters">
                <div class="col-md-3">
                   <div class="card account-left">
                      <div class="user-profile-header">
                         <img alt="logo" src="{{asset(CUSTOMER_PROFILE_PHOTO.'user.png')}}">
                         <h5 class="mb-1 text-secondary"><strong>Hi </strong> {{Auth::guard('customer')->user()->first_name}}&nbsp;{{Auth::guard('customer')->user()->last_name}}</h5>
                         <p>{{Auth::guard('customer')->user()->mobile}}</p>
                      </div>
                      <div class="list-group">
                         <a href="{{url('/account/profile') }}" class="list-group-item list-group-item-action active"><i aria-hidden="true" class="mdi mdi-account-outline"></i>  My Profile</a>
                         <a href="{{url('/account/address') }}" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i>  My Address</a>
                         <a href="{{url('/account/password-change') }}" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-account-key"></i>  Password Change</a>
                         <a href="{{url('/account/order-history') }}" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-format-list-bulleted"></i>  Order History</a> 
                         <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-lock"></i>  Logout</a>
                         <form id="logout-form" action="{{url('/account-logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form> 
                      </div>
                   </div>
                </div>

                <?php //dd(Auth::guard('customer')->user()->first_name); ?>

                <div class="col-md-9">  
                   <div class="card card-body account-right">
                    <div class="widget">
                         <div class="section-header">
                            <h5 class="heading-design-h5">
                                 {{$page_title}}
                            </h5>
                         </div>
                         <form id="profile_form" method="POST" >
                           @csrf
                            <div class="row">
                               <div class="col-sm-6">
                                  <div class="form-group required">
                                     <label class="control-label">First Name</label>
                                     <input class="form-control border-form-control" name="first_name" value="{{Auth::guard('customer')->user()->first_name}}" placeholder="First Name" type="text">
                                     <span class="error error_first_name text-danger"></span>
                                  </div>
                               </div>
                               <div class="col-sm-6">
                                  <div class="form-group required">
                                     <label class="control-label">Last Name</label>
                                     <input class="form-control border-form-control" name="last_name" value="{{Auth::guard('customer')->user()->last_name}}" placeholder="Last Name" type="text">
                                     <span class="error error_last_name text-danger"></span>
                                    </div>
                               </div>
                            </div>
                            <div class="row">
                               <div class="col-sm-6">
                                 <div class="form-group required">
                                    <label class="control-label" for="mobile">Mobile No.</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><img style="width:30px;margin-right:5px;" src="{{asset('public/website-assets/img/Qatar-flag.jpg')}}" alt="Qatar Flag"/> +974</span>
                                        </div>
                                        <input type="text" name="mobile" placeholder="Mobile Number" id="mobile" value="@if (Auth::guard('customer')->check()) {{Auth::guard('customer')->user()->mobile}} @endif" class="form-control"  autocomplete="false">
                                        
                                    </div>
                                    <span class="error error_mobile text-danger"></span>
                                </div>
                               </div>
                               <div class="col-sm-6">
                                  <div class="form-group required">
                                     <label class="control-label">Email Address</label>
                                     <input class="form-control border-form-control" value="{{Auth::guard('customer')->user()->email}}" placeholder="Email Address" disabled="" type="email">
                                  </div>
                               </div>
                            </div>
                            <div class="row">
                               <div class="col-sm-12 text-right">
                                  <button type="button" id="save-btn" class="btn btn-success btn-lg"> Save Changes </button>
                               </div>
                            </div>
                         </form>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>
 </section>


 <script>
$('#save-btn').click(function() {
    // ajax adding data to database
    $.ajax({
        url: "{{url('/account/profile-update')}}",
        type: "POST",
        data: $('#profile_form').serialize(),
        dataType: "JSON",
        beforeSend: function() {
            $('.preload').show();
        },
        success: function(data) {
            if (data.success) {
               location.reload(true);
            } else {

                $("#profile_form").find('.error').text('');
                console.log(data.errors);
                $.each(data.errors, function(key, value) {
                    $('.form-group').find('.error_' +
                        key).text(value);
                });

                $('.preload').hide();

            }


        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error adding / update data');
        }
    });
});
      
 </script>

 
@endsection





@section('script')

@endsection
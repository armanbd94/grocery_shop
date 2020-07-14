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
                         <a href="{{url('/account/profile') }}" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-account-outline"></i>  My Profile</a>
                         <a href="{{url('/account/address') }}" class="list-group-item list-group-item-action active"><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i>  My Address</a>
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
                         <div class="section-header" style="margin-bottom: 20px;">
                            <h5 class="heading-design-h5">
                                 {{$page_title}}
                            </h5>
                            <button type="button" class="btn btn-secondary pull-right" id="add_address">Add Address</button>
                         </div>
                            <div class="row">
                               <div class="col-sm-12">
                                  <div class="order-list-tabel-main table-responsive">  
                                    <table id="addressTable" class="table table-bordered">
                                       {!! $addressview !!}
                                    </table>
                                  </div>     
                               </div>
                            </div>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </div>

      <!-- BEGIN:: MENU ADD/EDIT MODAL FORM -->
      <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">
                                &times;
                            </span>
                        </button>
                    </div>
                    <form method="POST" id="address_form">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" id="address_id" />  
                              <div class="form-group required">
                                    <label class="control-label">First Name</label>
                              <input name="first_name" id="f_first_name" class="form-control border-form-control" value="{{Auth::guard('customer')->user()->first_name}}" placeholder="First Name" type="text">
                                    <span class="error error_first_name text-danger"></span>
                                 </div>
                           
                                 <div class="form-group required">
                                    <label class="control-label">Last Name</label>
                                    <input name="last_name"  id="f_last_name" class="form-control border-form-control" value="{{Auth::guard('customer')->user()->last_name}}" placeholder="Last Name" type="text">
                                    <span class="error error_last_name text-danger">The last name field is required.</span>
                                 </div>                                      

                                 <div class="form-group required">
                                    <label class="control-label" for="mobile">Mobile No.</label>      
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><img style="width:30px;margin-right:5px;" src="{{asset('public/website-assets/img/Qatar-flag.jpg')}}" alt="Qatar Flag"/> +974</span>
                                        </div>
                                        <input type="text" name="mobile_no" placeholder="Mobile Number" id="f_mobile_no" value="{{Auth::guard('customer')->user()->mobile}}" class="form-control"  autocomplete="false">
                                    </div>
                                    <span class="error error_mobile_no text-danger"></span>
                                        
                                    </div>
                              
                              
                                 <div class="form-group required">
                                    <label class="control-label">City</label>
                                    <input class="form-control border-form-control" value="Doha" type="text" readonly="">
                                 </div>
                              
                              
                                 <div class="form-group required">
                                    <label class="control-label">District</label>
                                    {{-- <input name="district" id="f_district" class="form-control border-form-control" value="" placeholder="Post Code" type="text"> --}}
                                    <select name="district" id="f_district" class="form-control border-form-control"> 
                                        <option value="">Select Please</option>
                                        @if (!empty(Helper::district()))
                                        @foreach (Helper::district() as $district)
                                        <option value="{{$district->district_name}}">{{$district->district_name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span class="error error_district text-danger"></span>
                                 </div>
                                                                                                                          
                                 <div class="form-group required">
                                    <label class="control-label">Address</label>
                                    <input type="text" id="f_address" name="address" class="form-control border-form-control" value="" placeholder="Address">
                                    <span class="error error_address text-danger"></span>
                                 </div>
                                 <div class="form-group required">
                                    <label class="control-label">Default Address</label>
                                    <select id="f_is_default" name="is_default" class="form-control border-form-control">
                                        <option value="2">No</option>
                                        <option value="1">Yes</option>
                                    </select>    
                                    <span class="error error_is_default text-danger"></span>
                                 </div>
                                 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-close" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" id="save-btn"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END:: MENU ADD/EDIT MODAL FORM -->  
 </section>


 

 
@endsection



@section('script')
<link href="{{asset('public/website-assets/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('public/website-assets/plugins/sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


<script>

      $('#add_address').click(function () {
                      save_method = 'add';
                      $('#address_form')[0].reset();
                      $('#address_form #f_is_default').prop('disabled', false);
                      $("#address_form #parent_id").val('').trigger("chosen:updated");
                      $('#address_id').val('');
                      $(".error").each(function () {
                          $(this).empty();
                      });
                      $('#addressModal').modal({
                          keyboard: false,
                          backdrop: 'static'
                      })
                      $('.modal-title').html('<span>Add Address</span>');
                      $('#save-btn').text('Save');
                  });
                  //END: ON CLICK ADD MENU BUTTON & SHOW ADD MENU MODAL CODE
      
                  //BEGIN: ON CLICK EDIT MENU BUTTON FETCH MENU DATA AND SHOW IT IN EDIT MODAL FORM CODE
                 //   $('.edit_menu').click(function () {

                    $(document).on('click','.edit_address', function() { 
                    
                      var id = $(this).data('id');
                      save_method = 'update';
                      $('#address_form')[0].reset(); // reset form on modals
                      $(".error").each(function () {
                          $(this).empty();
                      });
      
                      $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                      //Ajax Load data from ajax
                      $.ajax({
                          url: "{{url('/account/edit-address')}}",
                          type: "POST",
                          dataType: "JSON",
                          data:{
                              'id':id
                          },
                          success: function (data) {

                              $('#address_form #address_id').val(data.address.id);
                              $('#address_form #f_first_name').val(data.address.first_name);
                              $('#address_form #f_last_name').val(data.address.last_name);
                              $('#address_form #f_mobile_no').val(data.address.mobile_no);
                              $('#address_form #f_district').val(data.address.district);
                              $('#address_form #f_address').val(data.address.address);

                              if(data.address.is_default==1)
                              {
                                $('#address_form #f_is_default').val(data.address.is_default);  
                                $('#address_form #f_is_default').prop('disabled', 'disabled');
                                
                              }else
                              {
                                $('#address_form #f_is_default').val(data.address.is_default);
                                $('#address_form #f_is_default').prop('disabled', false);
                              }
                              
                              $('#addressModal').modal('show');
                              $('.modal-title').html('<i class="fa fa-edit"></i> <span>Edit Address</span>');
                              $('#save-btn').text('Update');
      
                          },
                          error: function (jqXHR, textStatus, errorThrown) {
                              alert('Error get data from ajax');
                          }
                      });
                  });
      
      
          $('#save-btn').click(function () {
              var url;
              if (save_method == 'add') {
                  url = "{{url('/account/add-address')}}";
              } else {
                  url = "{{url('/account/update-address')}}";
              }
      
              // ajax adding data to database
              $.ajax({
                  url: url,
                  type: "POST",
                  data: $('#address_form').serialize(),
                  dataType: "JSON",
                  beforeSend: function () {
                      $('.ajax_loading').show();
                  },
                  success: function (data) {
                      if (data.success) {
                         // console.log(data);
                          $('#addressModal').modal('hide');                   
                          $('.ajax_loading').hide();
                          $('#addressTable').html('');
                          $('#addressTable').html(data.address);
                      } else {
                          $("#addressModal").find('.error').text('');
                         // console.log(data.errors);
                          $.each(data.errors, function (key, value) {
                              $('.form-group').find('.error_' +
                                  key).text(value);
                          });
                          $('.ajax_loading').hide();
                      }
      
      
                  },
                  error: function (jqXHR, textStatus, errorThrown) {
                      alert('Error adding / update data');
                  }
              });
          });
      
           //$('.delete_address').click(function() {

            $(document).on('click','.delete_address', function() {  
           // $(document).delegate('.delete_address', 'click', function() {    
                var id = $(this).data('id');
                swal({
                        title: "Are you sure?",
                        text: "It will be deleted permanently!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        confirmButtonText: 'Yes',
                        cancelButtonText: "No", 
                        closeOnConfirm: false,
                        closeOnCancel: false,
                        allowOutsideClick: false
                    },
                    function(isConfirm) {  
                        if (isConfirm) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                    url: '{{ url("/account/delete-address") }}',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        'id': id
                                    }
                                })
                                .done(function(response) {
                                    if (response.status =='success') {
                                        swal({
                                            title: "Deleted!",
                                            text: response.message,
                                            type: "success"
                                        },
                                        function(){
                                            $('#addressTable').html('');
                                            $('#addressTable').html(response.address);
                                        })
                                    } else if (response.status =='error') 
                                    {
                                        swal('Error deleting!',response.message,'error');
                                    }


                                })
                                .fail(function() {
                                    swal('Oops...','Something went wrong with ajax !','error');
                                });
                        } else {
                            swal("Cancelled", "Your Address is not delete :)", "error");
                        }
                    });




});
      
            
       </script>


@endsection
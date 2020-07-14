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
                         <a href="{{url('/account/address') }}" class="list-group-item list-group-item-action "><i aria-hidden="true" class="mdi mdi-map-marker-circle"></i>  My Address</a>
                         <a href="{{url('/account/password-change') }}" class="list-group-item list-group-item-action"><i aria-hidden="true" class="mdi mdi-account-key"></i>  Password Change</a>
                         <a href="{{url('/account/order-history') }}" class="list-group-item list-group-item-action active"><i aria-hidden="true" class="mdi mdi-format-list-bulleted"></i>  Order History</a> 
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
                         </div>
                            <div class="row">
                                 <div class="col-sm-12">
                                       <table class="table table-borderless">
                                             <tbody>
                                                <tr>
                                                <td>
                                                   
                                                <h5>Billing address</h5><br>  
                                                <strong>{{$order->bfname.' '.$order->blname}}</strong><br>
                                                @if($order->bmobile)
                                                 Mobile Number:  {{$order->bmobile}}<br>
                                                @endif
                                                @if($order->baddress)
                                                Address:  {{$order->baddress}}<br>{{$order->bdistrict}}<br>{{$order->bcity}}<br>
                                                @endif
                                                </td>

                                                <td>
                                                   <h5>Shipping address</h5><br>
                                                   <strong>{{$order->sfname.' '.$order->slname}}</strong><br>
                                                   @if($order->smobile)
                                                    Mobile Number:  {{$order->smobile}}<br>
                                                   @endif
                                                   @if($order->saddress)
                                                   Address:  {{$order->saddress}}<br>{{$order->sdistrict}}<br>{{$order->scity}}<br>
                                                   @endif
                                                   </td>
                                                   <td>
                                                      <b>INVOICE NO.:&nbsp;</b>#{{$order->invoice_no}} <br>                                                             
                                                      <b>Order Date:&nbsp;</b>{{date('d-M-Y',strtotime($order->created_at))}}<br>
                                                      <b>Delivery Date:&nbsp;</b>{{date('d-M-Y',strtotime($order->delivery_date))}}<br>
                                                      <b>Delivery Time:&nbsp;</b>{{$order->delivery_time}}<br>
                                                      <b>Delivery Status:&nbsp;</b>
                                                         @if($order->delivery_status==1)
                                                         <span class="badge2 badge-success">Delivered</span>
                                                         @else
                                                            <span class="badge2 badge-warning">Pending</span>
                                                         @endif                                                            
                                                   </td>
                                                </tr>
                                             </tbody>
                                       </table>   
                                     </div>
                               <div class="col-sm-12">  
                                  <div class="table-responsive">
                                    <table class="table table-bordered" id="invoice_table">
                                        <thead>
                                            <tr>
                                                <th width="5%" class="text-center">#</th>
                                                <th width="50%" class="text-left"> PRODUCT</th>
                                                <th width="15%" class="text-right"> PRICE</th>
                                                <th width="10%" class="text-center"> QTY</th>
                                                <th width="20%" class="text-right"> SUBTOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {!! $product_list !!}
                                        </tbody>
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
 </section>


 

 
@endsection



@section('script')

@endsection
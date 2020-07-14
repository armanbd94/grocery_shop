<?php

namespace App\Http\Controllers\CustomerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerVerificationController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('guest:customer');
    }

    public function verifyEmail($id,$token)
    {

            $verify = Customer::where(['id' => $id,'remember_token' => $token])->first();
            
            if(!empty($verify)){
                return view('website.auth.password-reset')->with('token',$token)
                ->with('page_title','Reset Password ');
               
            }else{
                return redirect('/error/404')->with('warning', "Sorry your email cannot be identified.");
            }
    }
}

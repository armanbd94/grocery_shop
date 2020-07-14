<?php

namespace App\Http\Controllers\CustomerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use Validator;
use Response;
use App\Rules\StrongPassword;
class CustomerResetPasswordController extends Controller
{
    protected $redirectTo = '/account-login';

    public function __construct()
    {
        $this->middleware('guest:customer');
    }

    public function resetPassword(Request $request)
    {

        
        $rules['token']    = 'required';
        $rules['email']    = 'required|string|email';
        $rules['password']  = ['required','confirmed', new StrongPassword];

        $validator = Validator::make($request->all(), $rules); //validation rules checking

        if ($validator->fails()) {
            $json = array(
                'errors' => $validator->errors()
            );
        } else {
            
            $customer = Customer::where(['email' => $request->email, 'remember_token' => $request->token])->update(['password' => Hash::make($request->password)]);
            if($customer){
                $json['success'] = 'Password resetted successfully';
            } else{
                $json['error'] = 'Error! Try again.';
               
            }    
        }
        return Response::json($json);
        
    }

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/account-login';
    }
}

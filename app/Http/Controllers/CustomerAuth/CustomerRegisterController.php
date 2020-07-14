<?php

namespace App\Http\Controllers\CustomerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use App\Models\Customer;
use Validator;
use Response;
use Session;
use App\Models\CustomerAddress;
use App\Rules\ValidPhone;
use App\Rules\StrongPassword;

class CustomerRegisterController extends Controller
{
    protected $redirectTo = '/';
    protected $rules = array(
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'email'      => 'required|string|email|max:255|unique:customers',

    );

    public function __construct()
    {
        $this->middleware('guest:customer')->except('logout');
    }

    protected function create(array $data)
    {
        if(!empty($data['group_type'])){
            if($data['group_type'] == 1){
                $user =  Customer::create([
                    'first_name'           => $this->validate($data['first_name']),
                    'last_name'            => $this->validate($data['last_name']),
                    'email'                => $this->validate($data['email']),
                    'mobile'               => $this->validate($data['mobile']),
                    'password'             => Hash::make($data['password']),
                    'group_type'           => $this->validate($data['group_type'])
                ]);
            }else if($data['group_type'] == 2){
                $user =  Customer::create([
                    'first_name'           => $this->validate($data['first_name']),
                    'last_name'            => $this->validate($data['last_name']),
                    'email'                => $this->validate($data['email']),
                    'mobile'               => $this->validate($data['mobile']),
                    'group_type'           => $this->validate($data['group_type'])
                ]);
            }
        }
        return $user;
    }

    public function showRegistrationForm()
    {
        $page_title = 'Register Account';
        return view('website.auth.register', compact('page_title'));
    }


    public function register(Request $request)
    {
        // $this->validator($request->all())->validate();
        if($request->ajax()){
            //group type = 1 [for registered user], group type = 2[for guest checkout user]
            if(!empty($request->group_type)){
                if($request->group_type == 1){
                    
                    //redirect = 1 [redirected to customer account page] and redirect = 2[redirected to checkout page]
                    if($request->redirect == 1){
                        $this->redirectTo = 'account/profile'; //set redirect path
                    }else{
                        $this->redirectTo = 'checkout'; //set redirect path
                        $this->rules['district']    = 'required|string';
                        $this->rules['address']     = 'required|string';
                    }
                    $this->rules['mobile']    = array('required', new ValidPhone);
                    $this->rules['password']  = 'required|confirmed|min:6';
                   // $this->rules['password']  = ['required','confirmed', new StrongPassword];
                    $this->rules['agree']     =  'required';
                    $validator = Validator::make($request->all(), $this->rules); //validation rules checking

                    if ($validator->fails()) {
                        $json = array(
                            'errors' => $validator->errors()
                        );
                        return Response::json($json);
                    } else {

                        event(new Registered($user = $this->create($request->all())));
                        $this->guard()->login($user);
                        Session::put('customer_id', $user->id);

                        if($request->redirect == 2){
                            $address = new CustomerAddress();
                            $address->customer_id  = $user->id;
                            $address->first_name   = $this->validate($request->first_name);
                            $address->last_name    = $this->validate($request->last_name);
                            $address->mobile_no    = $this->validate($request->mobile);
                            $address->address      = $this->validate($request->address);
                            $address->city         = 'Doha';
                            $address->district     = $this->validate($request->district);
                            $address->created_at   = DATE;
                            $address->updated_at   = DATE;

                            
                            if($address->save()){
                                Session::put('billing_id',$address->id);
                                if($request->same_address == 1){
                                    Session::put('shipping_id',$address->id);
                                    $json['same_address'] = 'yes';
                                }else{
                                    $json['same_address'] = 'no';
                                }
                            }
                        }
                        $json['success'] = $this->redirectTo;
                        
                        return Response::json($json);
                    }
                    
                }else{
                    $this->redirectTo = '/checkout';
                    $validator = Validator::make($request->all(), $this->rules);

                    if ($validator->fails()) {
                        $json = array(
                            'errors' => $validator->errors()
                        );
                        return Response::json($json);
                    } else {
                        $user = $this->create($request->all());
                        if($user){
                            $json['user'] = $user;
                        }
                        $json['success'] = $this->redirectTo;
                        return Response::json($json);
                    }

                }
            }

        }
        
    }


    protected function guard()
    {
        return Auth::guard('customer');
    }

    protected function registered(Request $request, $user)
    {
 
        // return redirect('/signup')->with('status', 'We sent you an activation code. Check your email and click on the link to verify.');
    }

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }
 

}

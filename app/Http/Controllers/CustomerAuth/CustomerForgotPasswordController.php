<?php

namespace App\Http\Controllers\CustomerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Mail;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class CustomerForgotPasswordController extends Controller
{
    

    public function __construct()
    {
        $this->middleware('guest:customer');
    }

    public function index(){
        return view('website.auth.forgot-password')
            ->with('page_title','Forgot Password');

    }

    //send link to mail
    public function sendLink(Request $request)
    {

        $request->validate(['email' => 'required|email']);
        $user = Customer::where(['email' => $request->email])->first();
        if($user){

            $data = ['email' => $user->email,
                    'receiver' => $user->first_name,
                    'subject' => "Reset Password",
                    'token' => url(config('app.url'))."verify/reset/".$user->id.'/'.$user->remember_token,
                    ];

            Mail::send('website.email-template.verify', $data, function($message) use ($data){
                $message->from(config('mail.username'),config('app.name'));
                $message->to($data['email']);
                $message->subject($data['subject']);
            });

            return redirect()->back()
                ->with('status','We have sent your password reset link!');
        }else{
            return redirect()->back()
            ->with('warning','Please provide a verified email address.');
        }
    }
}

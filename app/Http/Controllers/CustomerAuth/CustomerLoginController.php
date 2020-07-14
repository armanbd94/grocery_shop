<?php

namespace App\Http\Controllers\CustomerAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Validation\ValidationException;
use App\Models\Customer;
use Session;
use Socialite;
use App\Models\SocialAccount;

class CustomerLoginController extends Controller
{
    use ThrottlesLogins;

    public function showLoginForm()
    {
        $page_title = 'Login';
        return view('website.auth.login',compact('page_title'));
    }

    protected function guard()
    {
        return Auth::guard('customer');
    }

    protected $redirectTo = '/';


    public function __construct()
    {
        $this->middleware('guest:customer')->except('logout');
    }

    public function login(Request $request)
    {
        if(!empty($request->redirect)){
            if($request->redirect == 1){
                $this->redirectTo = '/account/profile';
            }elseif ($request->redirect == 2) {
                $this->redirectTo = '/checkout';
            }
        }
        
        $this->validateLogin($request);
        // dd($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }


    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ? : redirect()->intended($this->redirectPath());
    }


    protected function authenticated(Request $request, $user)
    {

        if($user->status != 1 && $user->group_type != 1){
            $this->guard()->logout();
            return back()->with('warning', 'You account is temporarily disabled. Please contact with admin to enable account.');
        }
        
        Session::put('customer_id', $user->id);
        // dd(Session::get('customer_id'));
        return redirect()->intended($this->redirectPath());
    }

    /*******************/
    public function redirectToProvider($provider)
    {
        $redirect_auth = redirect()->back()->getTargetUrl();
        Session::put('redirect_auth', $redirect_auth);
        return Socialite::driver($provider)->redirect();
    }


    public function handleProviderCallback($provider,Request $request)
    {

        if (!$request->has('code') || $request->has('denied')) {
            $this->redirectTo = Session::get('redirect_auth');
            return redirect($this->redirectTo);
        }
        
        try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect('/');
        }

        $authUser = $this->findOrCreateUser($user, $provider);
        $this->guard()->login($authUser, true);
        Session::put('customer_id', $authUser->id);
        $this->redirectTo = Session::get('redirect_auth');
        return redirect($this->redirectTo);
    }
    public function findOrCreateUser($providerUser, $provider)
    {
        $nameArray = explode(' ',$providerUser->getName());
        $first_name = $nameArray[0];
        $last_name = $nameArray[1];
        // dd($first_name.' '. $last_name);
        $account = SocialAccount::whereProviderName($provider)
                   ->whereProviderId($providerUser->getId())
                   ->first();
                   
        if ($account) {
            
            return $account->customer;
        } else {
            $user = Customer::whereEmail($providerUser->getEmail())->first();
            
            if (! $user) {
                $user = Customer::create([
                    'email' => $providerUser->getEmail(),
                    'first_name'  => $first_name,
                    'last_name'  => $last_name,
                    'created_at' => DATE,
                    'updated_at' => DATE
                ]);
            }
 
            $user->socialAccount()->create([
                'provider_id'   => $providerUser->getId(),
                'provider_name' => $provider,
            ]);
 
            return $user;
        }
    }
    /*******************/





    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }


    public function username()
    {
        return 'email';
    }


    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ? : redirect('/');
    }


    protected function loggedOut(Request $request)
    {

    }
    
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }
}

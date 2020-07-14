<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;


    // protected $redirectTo = '/';


    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'role_id'  => 'required|integer',
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            'mobile_no' => 'required|numeric',
            'gender'    => 'required|numeric',
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'role_id'              => $data['role_id'],
            'name'                 => $data['name'],
            'email'                => $data['email'],
            'password'             => Hash::make($data['password']),
            'mobile_no'            => $data['mobile_no'],
            'additional_mobile_no' => $data['additional_mobile_no'],
            'gender'               => $data['gender'],
            'address'              => $data['address']
        ]);
    }
}

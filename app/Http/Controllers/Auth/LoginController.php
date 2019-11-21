<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

   
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $finduser = User::where('provider_id', $user->id)->first();
   
        if($finduser){

            Auth::login($finduser);

            return redirect('/home');

        }else{
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'provider_id'=> $user->id,
                
            ]);

            Auth::login($newUser);

            return redirect()->back();
        }


        
    
}
}

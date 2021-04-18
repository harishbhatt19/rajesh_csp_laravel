<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\User;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    
    protected function credentials(Request $request)
    {
        if(is_numeric($request->get('email'))){
            return ['mob_no'=>$request->get('email'),'password'=>$request->get('password')];
        }
        return $request->only($this->username(), 'password');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if($this->guard()->validate($this->credentials($request))) {
            
            if(Auth::attempt(['mob_no' => $request->email, 'password' => $request->password,'user_type'=>3]))
            {
                return redirect()->intended('dashboard');
            }  
            elseif(Auth::attempt(['mob_no' => $request->email, 'password' => $request->password,'user_type'=>4])){
                return redirect()->intended('dashboard');
            }elseif(Auth::attempt(['mob_no' => $request->email, 'password' => $request->password,'user_type'=>2])){
                return redirect()->intended('dashboard');
            }elseif(Auth::attempt(['mob_no' => $request->email, 'password' => $request->password,'user_type'=>6])){
                return redirect()->intended('dashboard');
            }else 
            {
                $this->incrementLoginAttempts($request);

                return redirect()->back()->withInput($request->only('email', 'remember'))
                ->withErrors(['password' => 'Credentials do not match our database.']);
            }
        } else {
            $this->incrementLoginAttempts($request);
            
            return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
            'password' => 'Credentials do not match our database!']);
        }
    }
    
    
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/login');
    }
    
    public function getForgotPassword()
    {
        return view('auth.forgot_password');
    }
    
    
    
    
}

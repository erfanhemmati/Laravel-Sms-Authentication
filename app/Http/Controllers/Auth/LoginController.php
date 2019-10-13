<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistrationEvent;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Library\SmsCode;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'phone';
    }

    public function login(Request $request)
    {
        // Validate data
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') && $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // ------------------------------------------- //
        if($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();
            if ($user->status == 1 && $this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            } else {
                $this->incrementLoginAttempts($request);
                event(new UserRegistrationEvent($user));
                return redirect('/verify?phone=' . $user->phone);

            }
        }
        // ------------------------------------------- //

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
            // 'g-recaptcha-response' => 'required|recaptcha' ( if you uncomment this line, the google recaptcha is enabled )
        ] /*, [
            'g-recaptcha-response.required' => 'انتخاب کردن تیک امنیتی الزامی می باشد'
        ] */ );
    }
}

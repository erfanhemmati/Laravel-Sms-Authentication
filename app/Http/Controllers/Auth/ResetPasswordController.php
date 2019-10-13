<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }

    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());
        $tokenData = $this->tokenData($request);
        $user = $this->getUser($tokenData);

        if ($user && $user->phone == $request->input('phone')) {
            $user->update([
                'password' => bcrypt($request->input('password'))
            ]);

            Auth::guard()->login($user, true);
            return redirect($this->redirectTo);
        }

        return back()->withErrors(['phone' => 'شماره وارد شده با شماره شما مطابقت ندارد']);
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'phone' => 'required|digits:11|regex:/^09[0-9]{9}$/',
            'password' => 'required|confirmed|min:6',
        ];
    }

    private function getUser($tokenData)
    {
        return User::wherePhone($tokenData->phone)->first();
    }

    private function tokenData(Request $request)
    {
        $tokenData = DB::table(config('auth.passwords.users.table'))->whereToken($request->input('token'))->first();
        return $tokenData;
    }
}

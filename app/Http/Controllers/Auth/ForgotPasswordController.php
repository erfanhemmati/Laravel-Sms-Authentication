<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistrationEvent;
use App\Http\Controllers\Controller;
use App\Library\SmsCode;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
        $phone = $request->input('phone');
        $user = User::where('phone', $phone)->first();
        if ($user) {
            // dd($user->phone);
            $token = $this->createToken($user, config('auth.passwords.users.table'));
            if ($user->status == true) {
                $this->sendSmsLink($user, $token);
            } else {
                event( new UserRegistrationEvent($user));
                return redirect('/verify?phone=' . $user->phone);
            }
            return back()->with('status', 'لینک فعال سازی به تلفن همراه وارد شده ارسال شد.');
        } else {
            return redirect()->back()->withMessage("چنین کاربری با شماره وارد شده یافت نشد.");
        }
    }

    protected function validateEmail(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|min:11|max:11|regex:/^09[0-9]{9}$/',
            // 'g-recaptcha-response' => 'required|recaptcha' ( if you uncomment this line, the google recaptcha is enabled )
        ] /*, [
            'g-recaptcha-response.required' => 'انتخاب کردن تیک امنیتی الزامی می باشد'
        ] */ );
    }

    private function createToken($user, $tableName)
    {
        $passwordTable = DB::table($tableName);
        $password = $passwordTable->wherePhone($user->phone);
        $token = Str::random(60);

        if ($password->first()) {
            $password->update(['token' => $token, 'created_at' => Carbon::now()]);
        } else {
            $passwordTable->insert(
                ['phone' => $user->phone, 'token' => $token, 'created_at' => Carbon::now()]
            );
        }

        return $token;
    }

    private function sendSmsLink($user, $token)
    {
        SmsCode::send_token($user->phone, route('password.reset', $token));
    }
}

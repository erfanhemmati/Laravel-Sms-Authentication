<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistrationEvent;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Library\SmsCode;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/verify';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        return $this->registered($request, $user) ?: redirect('/verify?phone=' . $request->phone);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'min:11', 'max:11', 'regex:/^09[0-9]{9}$/', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            // 'g-recaptcha-response' => 'required|recaptcha' ( if you uncomment this line, the google recaptcha is enabled )
        ] /*, [
            'g-recaptcha-response.required' => 'انتخاب کردن تیک امنیتی الزامی می باشد'
        ] */ );
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        event(new UserRegistrationEvent($user));
    }

    public function getVerify()
    {
        $phone = \request()->phone;
        $user = User::where('phone', $phone)->first();
        if ($user)
            if ($user->status == false)
                return view('auth.verify_sms');
            else
                return redirect()->route('login')->withMessage('حساب کاربری شما قبلا فعال شده است');
        else
            return redirect()->route('register')->withMessage('خطایی به وجود آمده است - چنین کاربری در سیستم وجود ندارد');
    }

    public function doVerify(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|integer'
        ], [
            'code.required' => 'فیلد کد ارسالی الزامی می باشد',
            'code.integer' => 'فرمت فیلد کد ارسالی نادرست می باشد'
        ]);

        $user = User::where('verification_code', $request->code)->first();
        if ($user) {
            $user->status = true;
            $user->verification_code = null;
            $user->save();

            $this->guard()->login($user);
            return $this->registered($request, $user) ?: redirect(url('/home'));
            // return redirect()->route('login')->withMessage('حساب کاربری شما با موفقیت فعال شد');
        } else
            return redirect()->back()->withMessage('کد وارد شده نادرست می باشد');
    }
}

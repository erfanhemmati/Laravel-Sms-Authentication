<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistrationEvent;
use App\Http\Controllers\Controller;
use App\Library\SmsCode;
use App\User;
use DateTime;
use Illuminate\Http\Request;

class SmsVerifyController extends Controller
{
    // Send another code to user phone
    public function codeSend(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required'
        ]);

        $phone = $request->input('phone');
        $user = User::where('phone', $phone)->firstOrFail();

        $datetime1 = new DateTime(date('Y/m/d H:i:s', time())); // current time
        $datetime2 = new DateTime(date('Y/m/d H:i:s', session('sendtime'))); // last send sms time
        $interval = $datetime1->diff($datetime2);
        $minute = $interval->format('%i');

        if($minute >= 2) {
            // Send sms
            event(new UserRegistrationEvent($user));
            return redirect()->back()->with("success", "کد فعال سازی مجدد به تلفن همراه شما ارسال شد.");
        }
        else {
            // Show message to user
            return redirect()->back()->withMessage('برای ارسال پیامک مجدد باید 2 دقیقه صبر کنید.');
        }
    }
}

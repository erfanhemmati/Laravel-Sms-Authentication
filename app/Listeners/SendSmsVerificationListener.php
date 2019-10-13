<?php

namespace App\Listeners;

use App\Library\SmsCode;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSmsVerificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;
        if($user) {
            $user->verification_code = SmsCode::send($user->phone, $user->name);
            $time = time();
            session(['sendtime' => $time]);
            $user->save();
        }
    }
}

<?php

namespace App\Library;

class SmsCode
{
    // Send verification code in login and register
    public static function send($phone, $name)
    {
        $verification_code = mt_rand(1, 999999);
        $text = "$name عزیز \n کد فعال سازی شما برابر است با : $verification_code";
        $data = array(
            'Username' => env('RAYGANSMS_USERNAME', 'erfanhemmati'),
            'Password' => env('RAYGANSMS_PASSWORD', '741852963415263@eH'),
            'PhoneNumber' => env('RAYGANSMS_PHONE_NUMBER', '50002210003000'),
            'MessageBody' => $text,
            'RecNumber' => $phone,
            'Smsclass' => '1'
        );

        $ch = curl_init();
        $parameteres = http_build_query($data, 'flags_');
        $url = "https://RayganSMS.com/SendMessageWithUrl.ashx?" . $parameteres;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        // echo $output;
        return $verification_code;
    }

    // Send reset password token to user phone
    public static function send_token($phone, $token)
    {
        $text = "بازگردانی رمز عبور \n $token";
        $data = array(
            'Username' => env('RAYGANSMS_USERNAME', 'erfanhemmati'),
            'Password' => env('RAYGANSMS_PASSWORD', '741852963415263@eH'),
            'PhoneNumber' => env('RAYGANSMS_PHONE_NUMBER', '50002210003000'),
            'MessageBody' => $text,
            'RecNumber' => $phone,
            'Smsclass' => '1'
        );
        $ch = curl_init();
        $parameteres = http_build_query($data, 'flags_');
        $url = "https://RayganSMS.com/SendMessageWithUrl.ashx?" . $parameteres;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
    }
}
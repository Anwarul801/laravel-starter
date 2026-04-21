<?php

namespace App\Services;

use App\Models\User;
use App\Models\OtpVerify;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class UserService
{

    public function sendOtp($contact)
    {

        $otpCode = rand(1000, 9999);

        $data = [
            'otp_code' => $otpCode
        ];

        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {

            $data['email'] = $contact;

            OtpVerify::updateOrCreate(
                ['email' => $contact],
                $data
            );

            Mail::raw("Your OTP code is {$otpCode}.", function ($message) use ($contact) {
                $message->to($contact)
                    ->subject('OTP Verification');
            });
        } else {

            $data['phone_number'] = $contact;

            OtpVerify::updateOrCreate(
                ['phone_number' => $contact],
                $data
            );
            $message = "Your OTP is {$otpCode} , Innova Institute";
            sms_send($contact, $message);
        }

        logger("OTP for {$contact} is {$otpCode}");

        return true;
    }

    public function verifyOtp($contact, $otp)
    {

        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {

            $record = OtpVerify::where('email', $contact)
                ->where('otp_code', $otp)
                ->first();
        } else {

            $record = OtpVerify::where('phone_number', $contact)
                ->where('otp_code', $otp)
                ->first();
        }

        return $record !== null;
    }

    public function createUser(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'phone' => preg_match('/^01[0-9]{9}$/', $data['contact'] ?? '') ? $data['contact'] : null,
            'email' => filter_var($data['contact'] ?? '', FILTER_VALIDATE_EMAIL) ? $data['contact'] : null,
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        return $user;
    }

    public function createUserFromLanding(array $data)
    {
        $plainPassword = rand(100000, 999999);

        $contact = $data['contact'] ?? null;

        $isPhone = preg_match('/^01[0-9]{9}$/', $contact);
        $isEmail = filter_var($contact, FILTER_VALIDATE_EMAIL);

        $user = User::create([
            'name' => $data['name'],
            'phone' => $isPhone ? $contact : null,
            'email' => $isEmail ? $contact : null,
            'password' => Hash::make($plainPassword),
            'email_verified_at' => now(),
        ]);

        $loginUrl = "https://innovainst.com/login";

        $message = "🎉 Welcome to Innovainst!\n\n";
        $message .= "Login Details:\n";
        $message .= "User: {$contact}\n";
        $message .= "Password: {$plainPassword}\n\n";
        $message .= "Login করুন:\n{$loginUrl}";

        if ($isPhone) {
            sms_send($user->phone, $message);
        }

        if ($isEmail) {
            Mail::raw($message, function ($mail) use ($contact) {
                $mail->to($contact)
                    ->subject('Your Account Login Details');
            });
        }

        return [
            'user' => $user,
            'password' => $plainPassword,
        ];
    }

    public function login($data)
    {

        $user = User::where(function ($query) use ($data) {
            $query->where('phone', $data['contact'])
                ->orWhere('email', $data['contact']);
        })->first();


        if (!$user) {
            throw new \Exception('অ্যাকাউন্ট পাওয়া যায়নি');
        }

        if ($user->status !== 'Active') {
            throw new \Exception('আপনার অ্যাকাউন্টটি নিষ্ক্রিয়');
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw new \Exception('পাসওয়ার্ড সঠিক নয়');
        }


        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }
        if (!empty($data['device_token'])) {

            UserDevice::updateOrCreate(
                [
                    'device_token' => $data['device_token'],
                ],
                [
                    'user_id' => $user->id,
                    'device_name' => $data['device_name'] ?? null,
                    'device_type' => $data['device_type'] ?? 'web',
                    'platform' => $data['platform'] ?? null,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'is_active' => true,
                    'last_used_at' => Carbon::now(),
                ]
            );
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'user' => $user
        ];
    }

    public function directLogin($token)
    {

        $user = User::where('shortcut_login_token', $token)->first();

        if (!$user) {
            return null;
        }

        $user->update([
            'shortcut_login_token' => null,
        ]);

        $authToken = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $authToken,
            'user' => $user
        ];
    }
}

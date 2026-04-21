<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Helpers\ResponseHelper;
use App\Http\Requests\ApiLoginRequest;
use App\Http\Requests\ApiRegisterRequest;
use App\Models\User;
use App\Services\UserProfileService;
use Illuminate\Support\Facades\Validator;
use Exception;

class AuthController extends Controller
{

    protected $userService;
    protected $profileService;

    public function __construct(UserService $userService, UserProfileService $profileService)
    {
        $this->userService = $userService;
        $this->profileService = $profileService;
    }

    public function sendOtpReg(Request $request)
    {
        $validated = $request->validate([
            'contact' => 'required'
        ]);

        $contact = $validated['contact'];

        if (preg_match('/^0\d{10}$/', $contact)) {

            if (User::where('phone', $contact)->exists()) {
                return ResponseHelper::error('এই নাম্বার দিয়ে ইতিমধ্যেই একটি অ্যাকাউন্ট রয়েছে।', 422);
            }
        } elseif (filter_var($contact, FILTER_VALIDATE_EMAIL)) {

            if (User::where('email', $contact)->exists()) {
                return ResponseHelper::error('এই ইমেইল দিয়ে ইতিমধ্যেই একটি অ্যাকাউন্ট রয়েছে।', 422);
            }
        } else {
            return ResponseHelper::error('সঠিক ফোন নাম্বার অথবা ইমেইল দিন।', 422);
        }

        try {

            $this->userService->sendOtp($contact);

            return ResponseHelper::success([], 'OTP sent successfully');
        } catch (\Throwable $e) {

            return ResponseHelper::error(
                'Failed to send OTP. ' . $e->getMessage(),
                500
            );
        }
    }

    public function sendOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'contact' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error($validator->errors()->first(), 422);
        }

        $type = $request->type;
        $contact = $request->contact;

        if ($type == "forget") {

            if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {

                $user = User::where('email', $contact)->first();
            } else {

                $user = User::where('phone', $contact)->first();
            }

            if (!$user) {
                return ResponseHelper::error('এই তথ্য দিয়ে কোনো একাউন্ট পাওয়া যায়নি।', 404);
            }
        }

        try {

            $this->userService->sendOtp($contact);

            return ResponseHelper::success([], 'OTP sent successfully');
        } catch (Exception $e) {

            return ResponseHelper::error('Failed to send OTP. ' . $e->getMessage(), 500);
        }
    }

    public function forgetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'contact' => 'required',
            'new_password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error($validator->errors()->first(), 422);
        }

        try {

            if (filter_var($request->contact, FILTER_VALIDATE_EMAIL)) {

                $user = User::where('email', $request->contact)->first();
            } else {

                $user = User::where('phone', $request->contact)->first();
            }

            if (!$user) {
                return ResponseHelper::error('User not found.', 403);
            }

            $updated = $this->profileService->setNewPassword($user, $request->new_password);

            if (!$updated) {
                return ResponseHelper::error('Current password is incorrect.', 403);
            }

            return ResponseHelper::success([], 'Password set successfully');
        } catch (\Exception $e) {

            return ResponseHelper::error('Failed to update password. ' . $e->getMessage(), 500);
        }
    }

    public function verifyOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'contact' => 'required',
            'otp_code' => 'required|numeric|digits:4',
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error($validator->errors()->first(), 422);
        }

        try {

            $verified = $this->userService->verifyOtp($request->contact, $request->otp_code);

            if (!$verified) {
                return ResponseHelper::error('Invalid or expired OTP', 401);
            }

            return ResponseHelper::success([], 'OTP verified successfully');
        } catch (Exception $e) {

            return ResponseHelper::error('Failed to verify OTP. ' . $e->getMessage(), 500);
        }
    }

    public function register(ApiRegisterRequest $request)
    {
        try {

            $user = $this->userService->createUser($request->validated());

            return ResponseHelper::success($user, 'User created successfully');
        } catch (Exception $e) {

            return ResponseHelper::error('Failed to create user. ' . $e->getMessage(), 500);
        }
    }
    public function landingRegister(ApiRegisterRequest $request)
    {
        try {

            $user = $this->userService->createUserFromLanding($request->validated());

            return ResponseHelper::success($user, 'User created successfully');
        } catch (Exception $e) {

            return ResponseHelper::error('Failed to create user. ' . $e->getMessage(), 500);
        }
    }

    public function login(ApiLoginRequest $request)
    {
        try {

            $data = $this->userService->login($request->validated());

          

            return ResponseHelper::success($data, 'Login successful');
        } catch (Exception $e) {

            return ResponseHelper::error('Login failed. ' . $e->getMessage(), 500);
        }
    }

    public function checkAccount(Request $request)
    {
        try {
            $request->validate([
                'contact' => 'required|string'
            ]);

            $contact = $request->input('contact');

            $user = User::where('email', $contact)
                ->orWhere('phone', $contact)
                ->first(); 

            if (!$user) {
                return ResponseHelper::success([
                    'exists' => false
                ], 'অ্যাকাউন্ট পাওয়া যায়নি');
            }

            $data = [
                'exists' => true,  
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone
            ];

            return ResponseHelper::success($data, 'অ্যাকাউন্ট পাওয়া গেছে');
        } catch (Exception $e) {
            return ResponseHelper::error('অ্যাকাউন্ট চেক করতে ব্যর্থ: ' . $e->getMessage(), 500);
        }
    }

    public function direct_login(Request $request)
    {

        $token = $request->query('token');

        $auth = $this->userService->directLogin($token);

        if (!$auth) {
            return response()->json(['status' => false, 'message' => 'Invalid or expired token'], 401);
        }

        return response()->json([
            'status' => true,
            'token' => $auth['token'],
            'user' => $auth['user']
        ]);
    }
}

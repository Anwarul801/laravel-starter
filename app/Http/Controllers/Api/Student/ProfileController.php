<?php
/**
 * @Author: Anwarul
 * @Date: 2026-01-20 10:36:41
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-22 10:46:40
 * @Description: Innova IT
 */

namespace App\Http\Controllers\Api\Student;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StudentProfileRequest;
use App\Services\UserProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(UserProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Get the profile of the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfile()
    {
        try {
            $profileData = $this->profileService->getProfile();

            return ResponseHelper::success($profileData, 'Profile return successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    /**
     * Update the profile of the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(StudentProfileRequest $request)
    {
        try {

            $validated = $request->validated();
            $profileFile = $request->hasFile('profile_image') ? $request->file('profile_image') : null;
            $profileData = $this->profileService->updateProfile(
                $validated,
                $profileFile
            );
            return ResponseHelper::success($profileData, 'Profile updated successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 400);
        }
    }

    public function updatePassword(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|'
        ]);

        if ($validator->fails()) {
            return ResponseHelper::error($validator->errors()->first(), 422);
        }

        try {
            $user =  Auth::guard('api')->user();

            $updated = $this->profileService->updatePassword($user, $request->current_password, $request->new_password);

            if (!$updated) {
                return ResponseHelper::error('Current password is incorrect.', 403);
            }

            return ResponseHelper::success([], 'Password updated successfully');
        } catch (\Exception $e) {
            return ResponseHelper::error('Failed to update password. ' . $e->getMessage(), 500);
        }
    }
}

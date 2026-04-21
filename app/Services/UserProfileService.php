<?php

/**
 * @ Author: Minhazul Abedin(Innova IT)
 * @ Create Time: 2025-05-07 12:06:10
 * @ modifiedBy: Minhazul Abedin (Senior Software Engineer)
 * @ Modified time: 2025-05-07 14:15:42
 * @ Description: All rights reserved to Innova IT
 */

namespace App\Services;

use App\Models\Institute;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\FileCustomizeTrait;
use Carbon\Carbon;

class UserProfileService
{
    public function getProfile()
    {
        $authUser = Auth::guard('api')->user();

        if (!$authUser) {
            throw new \Exception('User not found');
        }

        $userQuery = User::query()->where('id', $authUser->id);

        if ($authUser->is_affiliate == 1) {
            $userQuery
                ->withCount([
                    'enrolls as total_referrals'
                ])
                ->withSum([
                    'walletHistories as total_earnings' => function ($q) {
                        $q->where('type', 'credit');
                    }
                ], 'amount');
        }

        $user = $userQuery->with('affiliate')->first();

        return $user;
    }

    public function updateProfile(array $userData, $profileFile = null,)
    {
        $userId = Auth::guard('api')->user()->id;
        $user = User::find($userId);
        if (!$user) {
            throw new \Exception('User not found');
        }

        if (!empty($userData['new_password'])) {

            if (empty($userData['current_password'])) {
                throw new \Exception('Current password is required');
            }

            if (!Hash::check($userData['current_password'], $user->password)) {
                throw new \Exception('Current password is incorrect');
            }

            $user->password = Hash::make($userData['new_password']);
        }

        unset($userData['current_password'], $userData['new_password'], $userData['new_password_confirmation']);

        $user->fill($userData);


        $user->save();
        if (!empty($profileFile)) {

            if (!empty($user->profile_image) && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }

            $imgPath = FileCustomizeTrait::img_resize(
                $profileFile,
                '/storage/student/profiles/',
                400,
                400
            );

            $user->profile_image = $imgPath;
        } else {
            unset($user->profile_image);
        }

        $user->save();

        return [
            'user' => $user
        ];
    }


    public function updatePassword($user, $currentPassword, $newPassword)
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->password = $newPassword;
        // $user->text_password = $newPassword;
        $user->save();

        return true;
    }

    public function setNewPassword($user, $newPassword)
    {
        $user->password = $newPassword;
        $user->save();

        return true;
    }
}

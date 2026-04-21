<?php
/**
 * @Author: Anwarul
 * @Date: 2026-01-15 12:13:33
 * @LastEditors: Anwarul
 * @LastEditTime: 2026-01-15 12:13:54
 * @Description: Innova IT
 */

namespace App\Helpers;

class ResponseHelper
{
    public static function success($data = [], $message = 'Success', $status = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function error($message = 'Error', $status = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $status);
    }
}

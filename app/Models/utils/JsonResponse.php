<?php

namespace App\Models\utils;

/**
 * 全局封装json类
 */
class JsonResponse
{
    public static function success($data = null, $message = 'Success', $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        return self::response(true, $data, $message, $statusCode);
    }

    public static function error($message = 'Error', $statusCode = 500): \Illuminate\Http\JsonResponse
    {
        return self::response(false, null, $message, $statusCode);
    }

    private static function response($success, $data, $message, $statusCode): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => $success,
            'code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];
        return response()->json($response, $statusCode)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}

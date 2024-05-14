<?php


namespace App\Http\Services;

class ApiService
{
    public static function response($status = "", $data = "", string $message = "")
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ]);
    }

    public static function error(bool $status, string $message = "", $errors = "", int $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

}
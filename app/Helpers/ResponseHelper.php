<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ResponseHelper {
    public static function response200(string $message, $data = null) {
        $response = [
            'code' => 200,
            'message' => $message
        ];
        if(is_array($data)) $response = array_merge($response, ['data' => $data]);

        return new JsonResponse($response, 200);
    }

    public static function responseFailed(int $code, string $message = 'Terjadi kesalahan pada server') {
        return new JsonResponse([
            'code' => $code,
            'message' => $message
        ], $code);
    }

    public static function response422(string $message, array $errors) {
        return new JsonResponse([
            'code' => 422,
            'message' => $message,
            'errors' => $errors
        ], 422);
    }
}
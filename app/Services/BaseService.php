<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class BaseService
{
    public function handleResponse($data = [], string $message = '', int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $data,
            'messages' => $message,
        ];

        return response()->json(['data' => $response], $code);
    }

    public function handleError($errors = [], array|string $errorMsg = '', int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'messages' => $errors,
        ];

        if (!empty($errorMsg)) {
            $response['messages'][] = $errorMsg;
        }

        return response()->json(['data' => $response], $code);
    }

    public function handleException($exception)
    {
        $response['success'] = false;

        if (env('APP_DEBUG')) {
            $response['messages'][] = $exception->getMessage();
            $response['hint'][] = $exception->getTrace();
        } else {
            $response['messages'] = 'Something Went Wrong.';
        }

        return response()->json(['data' => $response], 409);
    }
}

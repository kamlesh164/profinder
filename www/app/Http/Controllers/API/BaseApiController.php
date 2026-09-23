<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class BaseApiController extends Controller
{
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function errorResponse(string $message = 'Error', $errors = null): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'errors'  => $errors,
        ], 401);
    }
}

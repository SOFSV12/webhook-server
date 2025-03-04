<?php

namespace App\Http\Trait;

trait ApiResponseTrait
{
     /**
     * Success response
     */
    public function successResponse($data, $message = 'Success', $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    /**
     * Error response
     */
    public function errorResponse($message, $status = 400, $errors = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }
}

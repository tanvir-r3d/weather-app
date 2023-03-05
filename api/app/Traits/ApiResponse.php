<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;

trait  ApiResponse
{
    /**
     * Success response send function
     *
     * @param mixed $data Main data pass.
     * @param string|null $message Some message.
     * @param int $code Status code pass.
     * @return \Illuminate\Http\Response
     */
    protected function successResponse(mixed $data = null, string $message = null, int $code = Response::HTTP_OK): \Illuminate\Http\Response
    {
        return response([
            'status' => 'Success',
            'message' => $message,
            'code' => $code,
            'data' => $data,
        ], $code);
    }

    /**
     * Error response send function
     *
     * @param string $message Some message.
     * @param int $code Status code pass.
     * @return \Illuminate\Http\Response
     */
    protected function errorResponse(string $message, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): \Illuminate\Http\Response
    {
        return response([
            'status' => 'Error',
            'message' => $message,
            'code' => $code,
            'data' => null,
        ], $code);
    }
}

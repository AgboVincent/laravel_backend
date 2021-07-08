<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class Output
{
    /**
     * Send a success response.
     *
     * @param null|string|object|array $data
     * @param int $response
     * @return JsonResponse
     */
    public static function success($data = null, int $response = Response::HTTP_OK): JsonResponse
    {
        return self::send(['data' => $data], $response);
    }

    /**
     * Send response out.
     *
     * @param object|array $data
     * @param int $response
     * @return JsonResponse
     */
    public static function send($data, int $response = Response::HTTP_OK): JsonResponse
    {
        return response()->json((array)$data, $response);
    }

    /**
     * Send out an error response.
     *
     * @param string|array|object $message
     * @param int $response
     * @return JsonResponse
     */
    public static function error($message = 'An Error Occurred', int $response = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return self::send(['data' => $message], $response);
    }
}

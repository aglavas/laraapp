<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Success data response
     *
     * @param $data
     * @param $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function successDataResponse($data, $statusCode)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data
        ])->setStatusCode($statusCode);
    }

    /**
     * Error message response
     *
     * @param $message
     * @param $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorMessageResponse($message, $statusCode)
    {
        return response()->json([
            'error' => [
                [
                    'type' => "general",
                    'field' => null,
                    'message' => $message
                ]
            ]
        ], $statusCode);
    }

    /**
     * Success message response
     *
     * @param $message
     * @param $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function successMessageResponse($message, $statusCode)
    {
        return response()->json([
            'status' => 'success',
            'message'=> $message
        ], $statusCode);
    }
}

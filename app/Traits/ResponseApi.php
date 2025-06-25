<?php

namespace App\Traits;

trait ResponseApi
{
    public function sendResponse($message, $data = null, $statusCode = 200, $errCode = null)
    {
        $response = [
            'status' => $statusCode < 400 ? 'success' : 'error',
            'message' => $message,
        ];

        if ($errCode) {
            $response['errCode'] = $errCode;
        }
        if ($data) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }


    public function sendError($message, $statusCode = 400, $errCode = null)
    {
        return $this->sendResponse($message, null, $statusCode, $errCode);
    }

    public function sendData($message, $data)
    {
        return $this->sendResponse($message, $data);
    }
}

<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function success($data = [], $key = 'data', $statusCode = 200, $status = 'success')
    {
        $response = [
            'status' => 'success',
        ];
        if ($data) {
            $response[$key] = $data;
        }

        return response()->json($response)->setStatusCode($statusCode);
    }
}

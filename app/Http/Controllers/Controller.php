<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @param int $code
     * @param string $message
     *
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    protected function error (int $code, string $message = '') {
        return response([
            'code' => $code,
            'message' => $message,
        ], $code);
    }
}

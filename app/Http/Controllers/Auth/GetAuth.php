<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GetAuth extends Controller
{
    /**
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle()
    {
        $user = Auth::user();
        if ($user === null) {
            return parent::error(401);
        }
        return response($user->getData('detail'));
    }
}
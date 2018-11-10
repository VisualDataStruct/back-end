<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Detail extends Controller
{
    /**
     * @param Request $request
     * @param string $user_id
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle(Request $request, string $user_id)
    {
        $auth = Auth::user();
        if ($auth === null) {
            return parent::error(401);
        }
        if (!$auth->isAdmin && $auth->id !== $user_id) {
            return parent::error(403);
        }
        $user = User::find($user_id);
        if ($user === null) {
            return parent::error(404);
        }
        return response($user->getData('detail'));
    }
}
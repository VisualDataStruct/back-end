<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangePassword extends Controller
{
    public function handle(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|string|size:64',
            'new_password' => 'required|string|size:64',
        ]);
        $user = Auth::user();
        if ($user === null) {
            return parent::error(401, '您未登录');
        }
        if (!$user->checkPassword($request->input('old_password', ''))) {
            return parent::error(401, '密码错误');
        }
        $user->password = $request->input('new_password');
        $user->save();
        $api_token = new ApiToken();
        $api_token->setExpiredTime(false);
        $user->apiTokens()->save($api_token);
        return response([
            'token' => $api_token->token,
        ]);
    }
}
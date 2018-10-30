<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\Request;

class Login extends Controller
{
    public function handle(Request $request)
    {
        $this->validate($request, [
            'username' => 'nullable|string',
            'email' => 'required_without:username|email',
            'password' => 'required|string|size:64',
            'remember' => 'nullable|boolean'
        ]);
        $user = User::where('username', $request->input('username', ' '))
                    ->orWhere('email', $request->input('email', ' '))->first();
        if ($user === null) {
            return parent::error(404, '找不到用户');
        }
        if (!$user->checkPassword($request->input('password', ''))) {
            return parent::error(401, '密码错误');
        }
        $api_token = new ApiToken();
        $api_token->setExpiredTime($request->input('remember', false));
        $api_token->save();
        return response([
            'token' => $api_token->token,
        ]);
    }
}
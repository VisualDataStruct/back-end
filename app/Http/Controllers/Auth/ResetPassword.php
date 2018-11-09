<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Verify;
use Illuminate\Http\Request;

class ResetPassword extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'verify' => 'required|string|size:6',
            'new_password' => 'required|string|size:64',
        ]);
        $email = $request->input('email');
        $user = User::where('email', '=', $email)->first();
        if ($user === null) {
            return parent::error(404, '邮箱未注册');
        }
        $verify = Verify::where('email', '=', $email)->latest('created_at')->first();
        if ($verify === null) {
            return parent::error(404, '该邮箱未生成验证码');
        }
        if (!$verify->checkVerify($request->input('verify')) || $verify->user->id !== $user->id) {
            return parent::error(401, '验证码错误');
        }
        if ($verify->isExpired) {
            return parent::error(401, '验证码过期');
        }
        $user->password = $request->input('new_password');
        $user->save();
        return response('');
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use App\Models\User;
use App\Models\Verify;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class ForgetPassword extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        $email = $request->input('email');
        $user = User::where('email', '=', $email)->first();
        if ($user === null) {
            return parent::error(404, '该邮箱未注册');
        }
        $lastlyVerify = Verify::where('email', '=', $email)->latest('expired_at')->first();
        if ($lastlyVerify !== null &&
            $lastlyVerify->noLongerThenOneMinute()) {
            return parent::error(429, '请在' . $lastlyVerify->secondToOneMinute() . '秒后重试');
        }
        $verify = new Verify();
        $verify->email = $email;
        Mail::to($verify->email)->send(new ForgetPasswordMail($verify));
        $verify->save();
        return response('Success');
    }
}
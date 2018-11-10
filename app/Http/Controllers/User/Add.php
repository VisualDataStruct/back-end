<?php

namespace App\Http\Controllers\User;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Mail\NewUserMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Add extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle(Request $request)
    {
        $user = Auth::user();
        if ($user === null) {
            return parent::error(401);
        }
        if (!$user->isAdmin) {
            return parent::error(403);
        }
        $this->validate($request, [
            'username' => 'required|string|unique:user,username',
            'realName' => 'required|string',
            'email' => 'required|string|unique:user,email',
            'github' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);
        $new_user = new User();
        $password = Helper::generatePassword();
        $new_user->username = $request->input('username');
        $new_user->realName = $request->input('realName');
        $new_user->email = $request->input('email');
        $new_user->github = $request->input('github', '');
        $new_user->phone = $request->input('phone', '');
        $new_user->password = Helper::sha256($password);
        $new_user->save();
        Mail::to($new_user->email)->send(new NewUserMail($new_user->username, $password));
        return response([
            'id' => $new_user->id,
        ]);
    }
}
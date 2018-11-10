<?php

namespace App\Http\Controllers\User;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Mail\NewUserMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Change extends Controller
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
        $this->validate($request, [
            'username' => 'string|unique:user,username|nullable',
            'email' => 'string|unique:user,email|nullable',
            'github' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);
        $user = User::find($user_id);
        $user->username = $request->input('username', $user->username);
        $user->email = $request->input('email', $user->email);
        $user->github = $request->input('github', $user->github);
        $user->phone = $request->input('phone', $user->phone);
        $user->save();
        return response('');
    }
}
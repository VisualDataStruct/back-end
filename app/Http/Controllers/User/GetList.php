<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class getList extends Controller
{
    /**
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle()
    {
        $users = User::query()->orderBy('contribution', 'desc')->get();
        $response = [];
        foreach ($users as $user) {
            $response[] = $user->getData('list');
        }
        return response($response);
    }
}
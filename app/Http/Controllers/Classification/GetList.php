<?php

namespace App\Http\Controllers\Classification;

use App\Http\Controllers\Controller;
use App\Models\Classification;
use Illuminate\Support\Facades\Auth;

class GetList extends Controller
{
    /**
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle()
    {
        $user = Auth::user();
        if ($user === null) {
            $classifications = Classification::all();
        } else {
            $classifications = Classification::withTrashed()->get();
        }
        $response = [];
        foreach ($classifications as $classification) {
            $response[] = $classification->getData('list');
        }
        return response($response);
    }
}
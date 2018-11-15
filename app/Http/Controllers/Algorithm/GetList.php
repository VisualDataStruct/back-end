<?php

namespace App\Http\Controllers\Algorithm;

use App\Http\Controllers\Controller;
use App\Models\Classification;
use Illuminate\Support\Facades\Auth;

class GetList extends Controller
{
    /**
     * @param string $classification_id
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle(string $classification_id)
    {
        $user = Auth::user();
        if ($user === null) {
            $classification = Classification::find($classification_id);
        } else {
            $classification = Classification::withTrashed()->find($classification_id);
        }
        if ($classification === null) {
            return parent::error(404);
        }
        return response($classification->getData('detail', $user !== null));
    }
}
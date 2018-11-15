<?php

namespace App\Http\Controllers\Algorithm;

use App\Http\Controllers\Controller;
use App\Models\Algorithm;
use App\Models\Classification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Restore extends Controller
{
    /**
     * @param Request $request
     * @param string $classification_id
     * @param string $algorithm_id
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle(Request $request, string $classification_id, string $algorithm_id)
    {
        $user = Auth::user();
        if ($user === null) {
            return parent::error(401);
        }
        $classification = Classification::withTrashed()->find($classification_id);
        if ($classification === null) {
            return parent::error(404, 'Classification Not Found');
        }
        $algorithm = $classification->algorithms()->withTrashed()->find($algorithm_id);
        if ($algorithm === null) {
            return parent::error(404, 'Algorithm Not Found');
        }
        $algorithm->restore();
        $classification->getSum();
        $classification->save();
        return response('');
    }
}
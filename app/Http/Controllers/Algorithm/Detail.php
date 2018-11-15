<?php

namespace App\Http\Controllers\Algorithm;

use App\Http\Controllers\Controller;
use App\Models\Classification;
use Illuminate\Support\Facades\Auth;

class Detail extends Controller
{
    /**
     * @param string $classification_id
     * @param string $algorithm_id
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle(string $classification_id, string $algorithm_id)
    {
        $user = Auth::user();
        if ($user === null) {
            $classification = Classification::find($classification_id);
        } else {
            $classification = Classification::withTrashed()->find($classification_id);
        }
        if ($classification === null) {
            return parent::error(404, 'Classification Not Found');
        }
        if ($user === null) {
            $algorithm = $classification->algorithms()->find($algorithm_id);
            if ($algorithm === null || !$algorithm->isPassed) {
                return parent::error(404, 'Algorithm Not Found');
            }
        } else {
            $algorithm = $classification->algorithms()->withTrashed()->find($algorithm_id);
            if ($algorithm === null) {
                return parent::error(404, 'Algorithm Not Found');
            }
        }
        return response($algorithm->getData('detail'));
    }
}
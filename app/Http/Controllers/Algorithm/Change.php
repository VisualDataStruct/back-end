<?php

namespace App\Http\Controllers\Algorithm;

use App\Http\Controllers\Controller;
use App\Models\Algorithm;
use App\Models\Classification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Change extends Controller
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
        $this->validate($request, [
            'name' => 'required|string|unique:algorithm,name',
            'pseudoCode' => 'required|array',
            'jsCode' => 'required|array',
            'explain' => 'required|array',
            'CPlusCode' => 'nullable|array',
        ]);
        $algorithm->name = $request->input('name', $algorithm->name);
        $algorithm->pseudoCode = $request->input('pseudoCode', $algorithm->pseudoCode);
        $algorithm->jsCode = $request->input('jsCode', $algorithm->jsCode);
        $algorithm->explain = $request->input('explain', $algorithm->explain);
        $algorithm->CPlusCode = $request->input('CPlusCode', $algorithm->CPlusCode);
        $algorithm->save();
        return response('');
    }
}
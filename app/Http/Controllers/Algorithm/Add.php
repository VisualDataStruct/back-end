<?php

namespace App\Http\Controllers\Algorithm;

use App\Http\Controllers\Controller;
use App\Models\Algorithm;
use App\Models\Classification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Add extends Controller
{
    /**
     * @param Request $request
     * @param string $classification_id
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle(Request $request, string $classification_id)
    {
        $user = Auth::user();
        if ($user === null) {
            return parent::error(401);
        }
        $classification = Classification::withTrashed()->find($classification_id);
        if ($classification === null) {
            return parent::error(404);
        }
        $this->validate($request, [
            'name' => 'required|string|unique:algorithm,name',
            'pseudoCode' => 'required|array',
            'jsCode' => 'required|array',
            'explain' => 'required|array',
            'CPlusCode' => 'nullable|array',
        ]);
        $algorithm = new Algorithm();
        $algorithm->name = $request->input('name');
        $algorithm->pseudoCode = $request->input('pseudoCode');
        $algorithm->jsCode = $request->input('jsCode');
        $algorithm->explain = $request->input('explain');
        $algorithm->CPlusCode = $request->input('CPlusCode', []);
        $classification->algorithms()->save($algorithm);
        return response([
            'id' => $algorithm->id,
        ]);
    }
}
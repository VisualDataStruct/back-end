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
            'blocksXml' => 'required|string',
            'blocksJson' => 'required|string',
            'CPlusCode' => 'nullable|array',
            'tagName' => 'required|string',
            'initVar' => 'nullable|array',
        ]);
        $algorithm = new Algorithm();
        $algorithm->name = $request->input('name');
        $algorithm->blocksXml = $request->input('blocksXml');
        $algorithm->blocksJson = $request->input('blocksJson');
        $algorithm->CPlusCode = $request->input('CPlusCode', []);
        $algorithm->tagName = $request->input('tagName');
        $algorithm->initVar = $request->input('initVar', []);
        $classification->algorithms()->save($algorithm);
        return response([
            'id' => $algorithm->id,
        ]);
    }
}
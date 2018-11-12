<?php

namespace App\Http\Controllers\Classification;

use App\Http\Controllers\Controller;
use App\Models\Classification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $this->validate($request, [
            'name' => 'required|string|unique:classification,name',
            'description' => 'nullable|string',
        ]);
        $classification = new Classification();
        $classification->name = $request->input('name');
        $classification->description = $request->input('description', '');
        $classification->save();
        return response([
            'id' => $classification->id,
        ]);
    }
}
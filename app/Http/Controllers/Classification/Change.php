<?php

namespace App\Http\Controllers\Classification;

use App\Http\Controllers\Controller;
use App\Models\Classification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Change extends Controller
{
    /**
     * @param Request $request
     * @param int $classification_id
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle(Request $request, int $classification_id)
    {
        $user = Auth::user();
        if ($user === null) {
            return parent::error(401);
        }
        $classification = Classification::find($classification_id);
        if ($classification === null) {
            return parent::error(404);
        }
        $this->validate($request, [
            'name' => 'nullable|string|unique:classification,name',
            'description' => 'nullable|string',
        ]);
        $classification->name = $request->input('name', $classification->name);
        $classification->description = $request->input('description', $classification->description);
        $classification->save();
        return response('');
    }
}
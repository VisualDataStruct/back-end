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
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'cover' => 'nullable|string',
        ]);
        $sameNameCount = Classification::where('name', $request->input('name', $classification->name))
            ->whereKeyNot($classification->id)->count();
        if ($sameNameCount > 0) {
            return parent::error(422, '名称 不能重复');
        }
        $classification->name = $request->input('name', $classification->name);
        $classification->description = $request->input('description', $classification->description);
        $classification->cover = $request->input('cover', $classification->cover);
        $classification->save();
        return response('');
    }
}
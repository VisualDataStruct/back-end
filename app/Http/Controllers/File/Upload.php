<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mimey\MimeTypes;
use PascalDeVink\ShortUuid\ShortUuid;

class Upload extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function handle (Request $request) {
        $user = Auth::user();
        if ($user === null) return parent::error(401);
        $this->validate($request, [
            'file' => 'file|required',
        ]);
        $file = $request->file('file');
        $filename = ShortUuid::uuid4();
        $mimeType = $file->getMimeType();
        $mimes = new MimeTypes();
        $extension = $mimes->getExtension($mimeType);
        $file->move(storage_path('app/public'), "$filename.$extension");
        return response([
            'filename' => "public/$filename.$extension",
        ]);
    }
}

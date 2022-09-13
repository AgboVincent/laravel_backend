<?php

namespace App\Http\Controllers\Upload;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Upload;


use App\Helpers\Output;
use App\Http\Requests\UploadRequest;
use App\Http\Resources\UploadResource;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FileUploadNew extends Controller
{
    public function __invoke(Request $request, Upload $model): JsonResponse
    {
        $path = Storage::putFile('uploads', $file = $request->file('file'), [
            'visibility' => Filesystem::VISIBILITY_PUBLIC
        ]);
 
        $model = $model->create([
            'size' => Storage::size($path),
            'mime' => Storage::mimeType($path),
            'ext' => $file->getClientOriginalExtension(),
            'path' => $path
        ]);

        return Output::success(new UploadResource($model), Response::HTTP_CREATED);

    }

}

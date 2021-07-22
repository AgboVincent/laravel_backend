<?php

namespace App\Http\Controllers\Upload;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadRequest;
use App\Http\Resources\UploadResource;
use App\Models\Upload;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewFileUpload extends Controller
{
    public function __invoke(UploadRequest $request, Upload $model): JsonResponse
    {
        $filepath = Storage::putFile('uploads', $file = $request->file('file'));

        $model = $model->create([
            'size' => Storage::size($filepath),
            'mime' => Storage::mimeType($filepath),
            'ext' => $file->getClientOriginalExtension(),
            'uploader' => Auth::id(),
            'path' => $filepath
        ]);

        return Output::success(new UploadResource($model), Response::HTTP_CREATED);
    }
}

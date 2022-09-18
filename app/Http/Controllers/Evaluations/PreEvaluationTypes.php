<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evaluations\PreEvaluationFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

class PreEvaluationTypes extends Controller
{
    public function __invoke(Request $request, PreEvaluationFile $type)
    {
        $path = Storage::putFile('uploads', $file = $request->file('file'), [
            'visibility' => Filesystem::VISIBILITY_PUBLIC
        ]);

        $id = 1;
        $ext = $file->getClientOriginalExtension();
        if($ext == 'mp4'){
            $id = 2;
        }
        if($ext == 'image'){
            $id = 1;
        }
        $type = $type->create([
            'pre_evaluation_id' => $request['id'],
            'vehicle_part' => $request['part'],
            'type_id' => $id,
            'url' => $path,
            'processing_status' => 'pending',
            'result' => 'unavailable',
        ]);

        return $type;
    }
}

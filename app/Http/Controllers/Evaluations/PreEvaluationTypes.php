<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evaluations\PreEvaluationFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use App\Models\Evaluations\PreEvaluationsModel;

class PreEvaluationTypes extends Controller
{
    public function store(Request $request, PreEvaluationFile $type)
    {
        $path = Storage::putFile('uploads', $file = $request->file('file'), [
            'visibility' => Filesystem::VISIBILITY_PUBLIC
        ]);

        $type = $type->create([
            'pre_evaluation_id' => $request['id'],
            'vehicle_part' => $request['part'],
            'type_id' => $request['type_id'],
            'url' => $path,
            'processing_status' => 'pending',
            'result' => 'unavailable',
        ]);

        return $type;
    }

    public function update(Request $request, PreEvaluationFile $type)
    {
        $array = $request->all();
        foreach($array as $res){
            $result = $type->where('url', '=', $res['path'])->update([
                'result' => $res['result'],
                'processing_status' => 'completed'
            ]);
        }
    }

    public function getFiles(Request $request, PreEvaluationFile $type)
    {
       $result = $type->where('pre_evaluation_id', '=', $request['id'])->get();
       $user = PreEvaluationsModel::where('id', '=',  $request['id'])->first();
       $user->uploads = $result;
       return $user;
    }
}

<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evaluations\PreEvaluationFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use App\Models\Evaluations\PreEvaluationsModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use App\Models\Evaluations\DetectedDamages;
use App\Services\PreEvaluationService;

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

    public function getFiles(Request $request)
    { 
       $data = new PreEvaluationService(); 
       return $data->getVettedUploads($request);
    }

    public function mlValidate(Request $request)
    {
        $response = Http::post(config('ml.url'),[
            "image_data1" => $request['image_data1'],
            "image_data2" => $request['image_data2'],
            "image_data3" => $request['image_data3'],
            "image_data4" => $request['image_data4']
        ]);
        return $response;
    }
}

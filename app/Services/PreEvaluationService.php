<?php namespace App\Services;

use App\Models\Evaluations\PreEvaluationsModel;
use App\Models\Evaluations\DetectedDamages;
use App\Models\Evaluations\VettedUploads;
use App\Models\Evaluations\PreEvaluationFile;

class PreEvaluationService
{
    public static function preEvaluation($request)
    {
        $request->validate([
            'name' => 'required|string|min:8|regex:/^(?=\S*\s\S*$)/',
            'email' => 'required|email|unique:pre_evaluations',
            'chassis_number' => 'required|string',
            'manufacturer' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|numeric', 
            'color' => 'required|string',
            'phone' => 'required|string|min:11|max:11',
            'vehicle_regno' => 'required|string',
            'engine_no' => 'required|string',
        ]);

       $user = PreEvaluationsModel::create([
            'name'=> $request['name'],
            'email'=> $request['email'],
            'chassis_number'=> $request['chassis_number'],
            'manufacturer'=> $request['manufacturer'],
            'model'=> $request['model'],
            'year'=> $request['year'],
            'color'=> $request['color'],
            'phone'=> $request['phone'],
            'vehicle_regno'=> $request['vehicle_regno'],
            'engine_no'=> $request['engine_no']
        ]);

        return $user->id;

    }

    public static function detectedDamages($request)
    {
        $front = [];
        $frontDamage = $request['front'];
        for($i = 0; $i < count($frontDamage); $i++){
            $front[$i+1] = $frontDamage[$i];
        }

        $rear = [];
        $rearDamage = $request['rear'];
        for($i = 0; $i < count($rearDamage); $i++){
            $rear[$i+1] = $rearDamage[$i];
        }

        $left = [];
        $leftDamage = $request['left'];
        for($i = 0; $i < count($leftDamage); $i++){
            $left[$i+1] = $leftDamage[$i];
        }

        $right = [];
        $rightDamage = $request['right'];
        for($i = 0; $i < count($rightDamage); $i++){
            $right[$i+1] = $rightDamage[$i];
        }

        $damages = DetectedDamages::create([
            'pre_evaluation_id'=> $request['id'],
            'front' => $front,
            'rear' => $rear,
            'left' => $left,
            'right' => $right
        ]);

    }

    public static function storeVettedUploads($request)
    {
        $uploads = [];
        $data = $request['uploads'];
        for($i = 0; $i < count($data); $i++){
            $uploads[$i]['url'] = $data[$i];
        }

        VettedUploads::create([
            'pre_evaluation_id' => $request['id'],
            'uploads' => $uploads
        ]);
    }

    public static function getVettedUploads($request)
    { 
        $output = [];
        $uploads = VettedUploads::where('pre_evaluation_id', '=', $request['id'])->first();
        $result = PreEvaluationFile::where('pre_evaluation_id', '=', $request['id'])->get();
        if($uploads != null){
            foreach($uploads['uploads'] as $upload){
                $data = $result->where('url', $upload['url'])->first();
                array_push($output,  $data);
            }
        }
        else{
            $output = $result;
        } 
        $user = PreEvaluationsModel::where('id', '=',  $request['id'])->first();
        $damages = DetectedDamages::where('pre_evaluation_id', $request['id'])->first();
        $user->uploads =  $output;
        $user->damages = $damages;
        return $user;      
    }

}
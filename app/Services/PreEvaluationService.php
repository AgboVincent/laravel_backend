<?php namespace App\Services;

use App\Models\Evaluations\PreEvaluationsModel;
use App\Models\Evaluations\DetectedDamages;

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
        $rightDamage = $request['rear'];
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

}
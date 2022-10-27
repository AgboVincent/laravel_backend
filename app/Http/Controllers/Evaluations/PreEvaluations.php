<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Evaluations\PreEvaluationsModel;

class PreEvaluations extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PreEvaluationsModel $data)
    {
        $data = $data->query()->get();
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(array $data)
    {
    

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,PreEvaluationsModel $data)
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

       $user = $data->create([
            'name'=> $request['name'],
            'email'=> $request['email'],
            'chassis_number'=> $request['chassis_number'],
            'manufacturer'=> $request['manufacturer'],
            'model'=> $request['model'],
            'year'=> $request['year'],
            'color'=> $request['color'],
            'phone'=> $request['phone'],
            'vehicle_regno'=> $request['vehicle_regno'],
            'engine_no'=> $request['engine_no'],
        ]);

        return $user->id;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

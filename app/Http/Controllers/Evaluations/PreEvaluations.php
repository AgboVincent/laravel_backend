<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreEvaluations extends Controller
{
    protected function Validator(array $data){
        return Validator::make($data, [
            'name' => 'required|string|min:5',
            'email' => 'required|email|unique:pre_evaluations',
            'chassis_number' => 'required|text',
            'manufacturer' => 'required|string',
            'model' => 'required|numeric',
            'year' => 'required|numeric',
            'status' => 'required|string',
            'color' => 'required|string',
            'estimate' => 'nullable|string',
            'evaluation_status' => 'nullable|string',
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        DB::table('pre_evaluations')->insert([
            'name'=> $request['name'],
            'email'=> $request['email'],
            'chassis_number'=> $request['chassis_number'],
            'manufacturer'=> $request['manufacturer'],
            'model'=> $request['model'],
            'year'=> $request['year'],
            'status'=> $request['status'],
            'color'=> $request['color'],
            'estimate'=> $request['estimate'],
            'evaluation_status'=> $request['evaluation_status'],
           // 'insurer_id' => 4
        ]);
        
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

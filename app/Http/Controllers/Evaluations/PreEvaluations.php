<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Evaluations\PreEvaluationsModel;
use App\Http\Resources\PaginatedResource;
use App\Services\PreEvaluationService;

class PreEvaluations extends Controller
{
    public function index(PreEvaluationsModel $data, Request $request)
    {
        $data = $data->query();
        $query = $request['query'];
        $date = $request['date'];
        if($query){
           $data->where('email', 'Like', '%' . $query . '%');
        }
        if($date){
            $data->whereBetween('created_at', [$request['startDate'], $request['endDate']]);
        }
        return $data->orderBy('id', 'DESC')->paginate(10);
    }
    
    public function store(Request $request, PreEvaluationService $data)
    {
        return $data->preEvaluation($request);      
    }

    public function detectedDamages(Request $request, PreEvaluationService $data)
    {
       return $data->detectedDamages($request);
    }
    
    public function storeVettedUploads(Request $request)
    {
       $data = new PreEvaluationService();
       return $data->storeVettedUploads($request);
    }
}

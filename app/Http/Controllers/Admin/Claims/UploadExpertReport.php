<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Models\Claim;
use App\Models\Expert;
use App\Helpers\Output;
use App\Models\ExpertReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Admin\ClaimResource;
use Illuminate\Contracts\Filesystem\Filesystem;

class UploadExpertReport extends Controller
{
    public function __invoke(Claim $claim, Expert $expert, Request $request)
    {
        $request->validate([
            'report' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:2048',
        ]);

        $filepath = Storage::putFile('uploads', $file = $request->file('report'), [
            'visibility' => Filesystem::VISIBILITY_PUBLIC
        ]);
        
        //check for empty report for this expert to this claim
        $report = ExpertReport::where('claim_id', $claim->id)
                                ->where('expert_id', $expert->id)
                                ->where('file_path', '')
                                ->where('file_name', '')
                                ->first();

        if ($report) {
            $report->file_path = $filepath;
            $report->file_name = $file->getClientOriginalName();
            $report->save();
        } else {
           $report = ExpertReport::create([
                'claim_id' => $claim->id,
                'expert_id' => $expert->id,
                'file_path' => $filepath,
                'file_name' => $file->getClientOriginalName(),
            ]);
        }

        return Output::success($report);
    }
}
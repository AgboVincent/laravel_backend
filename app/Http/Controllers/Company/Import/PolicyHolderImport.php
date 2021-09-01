<?php

namespace App\Http\Controllers\Company\Import;

use App\Helpers\Output;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\ImportRequest;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class PolicyHolderImport extends Controller
{
    public function __invoke(ImportRequest $request, Excel $import): JsonResponse
    {
        HeadingRowFormatter::default('none');
        $import->import(new \App\Imports\PolicyHolderImport(), $request->file('file'));

        return Output::success('Policy Holders Imported');
    }
}

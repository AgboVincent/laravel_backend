<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collections\ClaimsSubmission;
use App\Services\CollectionService;

class SubmitClaims extends Controller
{
    public function submit(Request $request, CollectionService $claim)
    {
        return $claim->submitClaim($request);
    }

    public function addQuotes(Request $request, CollectionService $quote)
    {
        return $quote->addQuotes($request);
    }

    public function updateClaimStatus (Request $request, CollectionService $status)
    {
        return $status->updateClaimStatus($request);
    }

    public function mlValidateClaimsUpload(Request $request, CollectionService $claimsUpload)
    {
        return $claimsUpload->mlValidateClaimsUpload($request);
    }
}

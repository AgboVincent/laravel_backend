<?php

namespace App\Http\Controllers\Admin\Claims;

use App\Models\Claim;
use App\Helpers\Output;
use App\Models\ClaimItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ClaimResource;

class AddItems extends Controller
{
    public function __invoke(Request $request, Claim $claim)
    {
        $request->validate([
            'items.*.type' => 'required',
            'items.*.quantity' => 'required|numeric',
            'items.*.amount' => 'required|numeric',
        ]);

        $items = [];
        $accidentId = $claim->accident()->first()->id;

        foreach ($request->items as $item) {
            $items[] = [
                'type_id' => $item['type'],
                'quantity' => $item['quantity'],
                'amount' => $item['amount'],
                'accident_id' => $accidentId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        ClaimItem::insert($items);

        $claim->touch();
        
        return Output::success(new ClaimResource($claim->load([
            'policy', 'accident.media', 'accident.thirdParties', 'accident.media.file',
            'items', 'user'
        ])));
    }
}
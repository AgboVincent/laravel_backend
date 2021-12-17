<?php

namespace App\Actions\Claims;

use App\Models\Claim;
use App\Models\ClaimFinancialMovement;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class ListFinancialMovements
{
    use AsAction;

    protected $movements;

    public function handle(Claim $claim)
    {
       $this->movements = $claim->financialMovements;

       $result = $this->calculateAmountForProvisions($this->movements);

       return ['data'=> $result];
    }

    protected function calculateAmountForProvisions(Collection $movements){

        return $movements->map(function ($row){

            if($row->nature==ClaimFinancialMovement::NATURE_PROVISION){
                $row = $this->subtractTotalOfSameGuarantees($row);
            }

           return $row;
        });
    }

    protected function subtractTotalOfSameGuarantees(ClaimFinancialMovement $movement){

        $matchingMovementsTotal = $this->movements
            ->where('id','!=',$movement->id)
            ->where('guarantees',$movement->guarantees)
            ->sum('amount');

        $movement->amount -= $matchingMovementsTotal;

        return $movement;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimFinancialMovement extends Model
{
    use HasFactory;

    const NATURE_PROVISION = 'provision';

    protected $casts = ['guarantees'=>'json'];

    protected $guarded = ['id'];

    public function claim(){
        return $this->belongsTo(Claim::class);
    }
}

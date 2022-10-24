<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedPolicy extends Model
{
    use HasFactory;
    protected $fillable = [
         'pre_evaluation_id',
         'policy_id', 
         'purchased_policy', 
         'payment_status' , 
         'evaluation_status'
    ];
}

<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Evaluations\NewPolicyItem;

class NewPolicy extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'amount', 'payment_duration' ];

    public function policyItem(){
        return $this->hasMany(NewPolicyItem::class, 'policy_id');
    }
}

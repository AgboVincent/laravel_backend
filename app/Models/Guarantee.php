<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guarantee extends Model
{
    use HasFactory;

    protected $appends = ['name'];
    
    protected $guarded = ['id'];

    public function type(){
        return $this->belongsTo(GuaranteeType::class, 'guarantee_type_id');
    }

    public function getNameAttribute(){
        return $this->type->name ?? '' ;
    }
}

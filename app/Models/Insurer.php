<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function brokers(){
        return $this->belongsToMany(Broker::class);
    }

    public function policies(){
        return $this->hasMany(Policy::class);
    }

    public function customers(){
        return $this->belongsToMany(User::class,'policies');
    }
}

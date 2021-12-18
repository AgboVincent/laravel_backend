<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    use HasFactory;

    public function insurers(){
        return $this->belongsToMany(Insurer::class);
    }

    public function policies(){
        return $this->hasMany(Policy::class);
    }

    public function customers(){
        return $this->belongsToMany(User::class,'policies');
    }
}

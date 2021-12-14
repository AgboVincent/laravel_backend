<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientResponsibility extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['name'];

    public function getNameAttribute(){
        return $this->value > 0 ? $this->value . '% responsible' : 'Not responsible';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    const GEAR_TYPE_AUTO = 'auto';
    const GEAR_TYPE_MANUEL = 'manual';
}

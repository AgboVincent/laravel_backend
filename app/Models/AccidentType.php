<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AccidentType
 * @package App\Models
 * @mixin Builder
 * @property int $id
 * @property string $name
 */
class AccidentType extends Model
{
    use HasFactory;

    public $timestamps = false;
}

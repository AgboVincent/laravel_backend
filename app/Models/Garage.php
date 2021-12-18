<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Garage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = ['address' => 'array'];

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function meta(): MorphMany
    {
        return $this->morphMany(Meta::class, 'owner');
    }
}

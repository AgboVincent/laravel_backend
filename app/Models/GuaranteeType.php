<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuaranteeType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['name'];

    // for backward compatibility
    // should be removed after updating vue components
    public function getNameAttribute(): string
    {
        return $this->attributes['code'] ?? '';
    }
}

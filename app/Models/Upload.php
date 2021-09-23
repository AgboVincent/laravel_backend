<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class Upload
 * @package App\Models
 * @mixin Builder
 */
class Upload extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getPathAttribute($value): string
    {
        return Storage::disk('s3')->url($value);
    }
}

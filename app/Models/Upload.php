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
        if(env('APP_ENV') == 'local') {
            return Storage::disk('uploads')->url($value);
        } else {
            return Storage::disk('s3')->url($value);
        }
    }
    protected $table = 'uploads_new';
}

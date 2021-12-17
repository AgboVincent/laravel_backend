<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpertReport extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['file'];
    // protected $hidden = ['file_path'];

    public function getFileAttribute(): string
    {
        if(env('APP_ENV') == 'local') {
            return Storage::disk('uploads')->url($this->file_path);
        } else {
            return Storage::disk('s3')->url($this->file_path);
        }
    }
}

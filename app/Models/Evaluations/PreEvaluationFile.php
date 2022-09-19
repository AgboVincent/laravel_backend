<?php

namespace App\Models\Evaluations;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Evaluations\VehicleFileType;

class PreEvaluationFile extends Model
{
    use HasFactory;
    protected $fillable = ['pre_evaluation_id','vehicle_part', 'type_id','url','processing_status', 'result'];

    protected $guarded = [];

    public function getPathAttribute($value): string
    {
        if(env('APP_ENV') == 'local') {
            return Storage::disk('uploads')->url($value);
        } else {
            return Storage::disk('s3')->url($value);
        }
    }
}

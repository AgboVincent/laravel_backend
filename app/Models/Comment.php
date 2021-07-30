<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Comment
 * @package App\Models
 * @mixin Builder
 * @property int $id
 * @property int $claim_id
 * @property string $comment
 * @property User $author
 * @property Claim $claim
 */
class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->author();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }
}

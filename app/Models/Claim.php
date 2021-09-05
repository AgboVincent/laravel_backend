<?php

namespace App\Models;

use App\Helpers\Auth;
use App\Models\Traits\RouteBinding;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Znck\Eloquent\Traits\BelongsToThrough;

/**
 * Class Claim
 * @package App\Models
 * @mixin Builder
 *
 * @property int $id
 * @property string $status
 * @property int $policy_id
 * @property bool $involves_insurer
 * @property Policy $policy
 * @property User $user
 * @property ClaimItem[]|Collection $items
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Accident $accident
 * @property Company $company
 * @property Comment[] $comments
 * @method static self filter(...$args)
 */
class Claim extends Model
{
    use HasFactory;
    use BelongsToThrough;
    use Filterable;
    use RouteBinding;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_ATTENTION_REQUESTED = 'attention requested';
    const STATUS_AWAITING_PAYMENT = 'awaiting payment';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DECLINED = 'declined';
    protected $guarded = [];

    public function notFoundMessage(): string
    {
        return 'Claim Not Found';
    }

    public function accident(): HasOne
    {
        return $this->hasOne(Accident::class);
    }

    public function user(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(
            User::class,
            Policy::class
        );
    }

    public function company(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(
            Company::class,
            Policy::class
        );
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    /**
     * Create a new comment on this claim.
     *
     * @param string $comment
     * @return Comment|Model
     */
    public function comment(string $comment): Model
    {
        try {
            Firebase::messaging()->send(
                CloudMessage::withTarget('token', $this->user->meta->fcm_token)
                    ->withNotification(Notification::create(Str::limit($comment), $comment))
                    ->withData(['claim_id' => $this->id])
            );
        } catch (MessagingException | FirebaseException | \Throwable | \Exception $e) {
        }

        return $this->comments()->create([
            'comment' => $comment,
            'involves_insurer' => $this->involves_insurer,
            'user_id' => Auth::user()->id
        ]);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function computeStatus()
    {
        $itemsCount = $this->items()->count();
        if ($this->items()->where('status', ClaimItem::STATUS_PENDING)->exists()) {
            $status = $this::STATUS_PENDING; // if there is a pending item, claim should be pending still
        } elseif (
            $this->items()->where('status', ClaimItem::STATUS_REJECTED)->count() === $itemsCount
        ) {
            $status = $this::STATUS_DECLINED; // if the number of rejected is exactly number of items, decline claim
        } else {
            $status = $this::STATUS_APPROVED; // else if every item is attended to either rejected or approved, approve claim
        }

        $this->update([
            'status' => $status
        ]);
    }

    public function items(): HasManyThrough
    {
        return $this->hasManyThrough(
            ClaimItem::class,
            Accident::class
        );
    }

    public function amount()
    {
        return $this->items()->where('status', ClaimItem::STATUS_APPROVED)->sum('amount');
    }
}

<?php

namespace App\Models;

use App\Enum\Payment\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => PaymentStatus::class,
    ];

    public function getRouteKey(): string
    {
        return 'unique_id';
    }

    /**
     * transaction
     *
     * @return HasOne
     */
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    protected static function booted()
    {
        static::creating(function ($payment) {
            $payment->unique_id = generateUniqueNumber(10);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * is popular
     * @param Builder $query
     * @return void
     */
    public function scopeIsPending(Builder $query): void
    {
        $query->where('status', PaymentStatus::PENDING->value);
    }

    /**
     * is approved
     * @param Builder $query
     * @return void
     */
    public function scopeIsApproved(Builder $query): void
    {
        $query->where('status', PaymentStatus::APPROVED->value);
    }


    /**
     * is rejected
     * @param Builder $query
     * @return void
     */
    public function scopeIsRejected(Builder $query): void
    {
        $query->where('status', PaymentStatus::REJECTED->value);
    }
}

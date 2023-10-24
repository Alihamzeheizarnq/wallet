<?php

namespace App\Models;

use App\Enum\Payment\PaymentStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * is active
     * @param Builder $query
     * @return void
     */
    public function scopeIsActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

}

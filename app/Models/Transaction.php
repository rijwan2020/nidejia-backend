<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id',
        'start_date',
        'end_date',
        'price_per_day',
        'total_days',
        'fee',
        'total_price',
        'status',
    ];

    public function setListingIdAttribute($value)
    {
        $listing = Listing::find($value);
        $totalDays = Carbon::createFromDate($this->attributes['start_date'])->diffInDays(Carbon::createFromDate($this->attributes['end_date'])) + 1;
        $totalPrice = $listing->price_per_day * $totalDays;
        $fee = $totalPrice * 0.1;

        $this->attributes['listing_id'] = $value;
        $this->attributes['price_per_day'] = $listing->price_per_day;
        $this->attributes['total_days'] = $totalDays;
        $this->attributes['fee'] = $fee;
        $this->attributes['total_price'] = $totalPrice + $fee;
    }

    /**
     * The user that this transaction belongs to.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The listing that this transaction belongs to.
     *
     * @return BelongsTo
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

}

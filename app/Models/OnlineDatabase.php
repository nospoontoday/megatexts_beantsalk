<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\LogOptions;

class OnlineDatabase extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'publisher_id',
        'platform_id',
        'access_model_id',
        'subscription_period',
    ];

    protected $appends = [
        'total_amount',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function accessModel()
    {
        return $this->belongsTo(AccessModel::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Get all of the user's addresses.
     */
    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    /**
     * Get the user's most recent contact info.
     */
    public function photo()
    {
        return $this->morphOne(Photo::class, 'photoable')->latestOfMany();
    }

    public function getTotalAmountAttribute()
    {
        return (
            $this->product->quantity * 
            $this->product->price
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['subscription_period'])
            ->logOnlyDirty()
            ->useLogName('OnlineDatabase')
            ->dontSubmitEmptyLogs();
    }
}

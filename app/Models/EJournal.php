<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\LogOptions;

class EJournal extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'platform_id',
        'product_id',
        'editor_id',
        'publisher_id',
        'e_issn',
        'frequency',
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

    public function editor()
    {
        return $this->belongsTo(Editor::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function accessModel()
    {
        return $this->belongsTo(AccessModel::class);
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
            ->logOnly(['e_issn', 'frequency', 'subscription_period', 'subject'])
            ->logOnlyDirty()
            ->useLogName('EJournal')
            ->dontSubmitEmptyLogs();
    }
}

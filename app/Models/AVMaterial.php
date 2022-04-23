<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\LogOptions;


class AVMaterial extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'author_id',
        'publisher_id',
        'item_code',
        'publication_year',
        'discount',
    ];

    protected $appends = [
        'total_amount',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
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


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['item_code', 'publication_year', 'discount'])
            ->logOnlyDirty()
            ->useLogName('AVMaterial')
            ->dontSubmitEmptyLogs();
    }

    public function getTotalAmountAttribute()
    {
        return (
            $this->product->quantity * 
            $this->product->price
        ) - $this->product->discount;
    }
}

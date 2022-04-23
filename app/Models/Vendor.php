<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class Vendor extends Model
{
    use HasFactory, LogsActivity, CausesActivity;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact_person',
    ];

    /**
     * Get all of the user's addresses.
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get all of the user's addresses.
     */
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    /**
     * Get the user's most recent contact info.
     */
    public function contact()
    {
        return $this->morphOne(Contact::class, 'contactable')->latestOfMany();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps()->withPivot('quantity', 'price');
    }


    /**
     * Get the user's most recent address.
     */
    public function presentAddress()
    {
        return $this->morphOne(Address::class, 'addressable')->latestOfMany();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'contact_person'])
            ->logOnlyDirty()
            ->useLogName('Vendor')
            ->dontSubmitEmptyLogs();
    }
}

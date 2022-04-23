<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\LogOptions;

class Customer extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_name',
        'buyer_name',
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
            ->logOnly(['company_name', 'buyer_name'])
            ->logOnlyDirty()
            ->useLogName('Customer')
            ->dontSubmitEmptyLogs();
    }
}

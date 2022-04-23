<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class Quotation extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_title',
        'pr_number',
        'deadline',
        'bidding_date',
        'status',
        'purpose_id',
        'terms_conditions',
    ];

    public function purpose()
    {
        return $this->belongsTo(Purpose::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps()->withPivot('quantity', 'price', 'discount');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['project_title', 'pr_number', 'deadline', 'bidding_date', 'status'])
            ->logOnlyDirty()
            ->useLogName('Quotation')
            ->dontSubmitEmptyLogs();
    }
}

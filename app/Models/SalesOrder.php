<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class SalesOrder extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'branch_id',
        'order_summary',
        'total_amount',
        'status',
        'date',
        'reference_number',
        'iorf_number',
        'user_id',
    ];

    protected $appends = [
        'sales_order_total_amount',
        'sales_rep_full_name',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps()->withPivot('product_id', 'quantity', 'price', 'discount');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSalesRepFullNameAttribute()
    {
        $first_name = $this->user->first_name ?? null;
        $last_name = $this->user->last_name ?? null;
        return $first_name . " " . $last_name;
    }

    public function getSalesOrderTotalAmountAttribute()
    {
        $totalAmount = 0;
        $totalDiscount = 0;
        foreach($this->products as $product) {
            $totalAmount += $product->pivot->price;
            $totalDiscount += $product->pivot->discount;
        }

        return $totalAmount - $totalDiscount;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['customer_id', 'branch_id', 'order_summary', 'total_amount', 'status', 'date', 'reference_number', 'iorf_number'])
            ->logOnlyDirty()
            ->useLogName('SalesOrder')
            ->dontSubmitEmptyLogs();
    }
}

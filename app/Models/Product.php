<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'quantity',
        'price',
        'subject',
        'type_id',
        'is_quotation',
        'is_vendor',
    ];

    protected $appends = [
        'vendor_price',
        'vendor_quantity',
        'vendor_total_amount',
        'vendor_vatable_sales',
        'vendor_vat',
        'quotation_price',
        'quotation_quantity',
        'quotation_discount',
        'quotation_total_amount',
    ];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function printBook()
    {
        return $this->hasOne(PrintBook::class);
    }

    public function printJournal()
    {
        return $this->hasOne(PrintJournal::class);
    }

    public function AVMaterial()
    {
        return $this->hasOne(AVMaterial::class);
    }

    public function libraryFixture()
    {
        return $this->hasOne(LibraryFixture::class);
    }

    public function eBook()
    {
        return $this->hasOne(EBook::class);
    }

    public function eJournal()
    {
        return $this->hasOne(EJournal::class);
    }

    public function onlineDatabase()
    {
        return $this->hasOne(OnlineDatabase::class);
    }

    public function libraryTechnology()
    {
        return $this->hasOne(LibraryTechnology::class);
    }

    public function quotations()
    {
        return $this->belongsToMany(Quotation::class)->withTimestamps()->withPivot('quantity', 'price', 'discount');
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class)->withTimestamps()->withPivot('quantity', 'price');
    }

    public function salesOrders()
    {
        return $this->belongsToMany(SalesOrder::class)->withTimestamps()->withPivot('quantity', 'price', 'discount');
    }

    public function getVendorPriceAttribute()
    {
        foreach($this->vendors as $vendor) {
            if($vendor->pivot->product_id == $this->id) {
                return $vendor->pivot->price;
            }
        }
    }

    public function getQuotationPriceAttribute()
    {
        foreach($this->quotations as $quotation) {
            if($quotation->pivot->product_id == $this->id) {
                return $quotation->pivot->price;
            }
        }
    }

    public function getVendorQuantityAttribute()
    {
        foreach($this->vendors as $vendor) {
            if($vendor->pivot->product_id == $this->id) {
                return $vendor->pivot->quantity;
            }
        }
    }

    public function getQuotationQuantityAttribute()
    {
        foreach($this->quotations as $quotation) {
            if($quotation->pivot->product_id == $this->id) {
                return $quotation->pivot->quantity;
            }
        }
    }

    public function getVendorTotalAmountAttribute()
    {
        foreach($this->vendors as $vendor) {
            if($vendor->pivot->product_id == $this->id) {
                return $vendor->pivot->price * $vendor->pivot->quantity;
            }
        }
    }

    public function getQuotationTotalAmountAttribute()
    {
        foreach($this->quotations as $quotation) {
            if($quotation->pivot->product_id == $this->id) {
                return $quotation->pivot->price * $quotation->pivot->quantity;
            }
        }
    }

    public function getQuotationDiscountAttribute()
    {
        foreach($this->quotations as $quotation) {
            if($quotation->pivot->product_id == $this->id) {
                return $quotation->pivot->discount;
            }
        }
    }

    public function getVendorVatableSalesAttribute()
    {
        foreach($this->vendors as $vendor) {
            if($vendor->pivot->product_id == $this->id) {
                return $vendor->pivot->vatable_sales;
            }
        }
    }

    public function getVendorVatAttribute()
    {
        foreach($this->vendors as $vendor) {
            if($vendor->pivot->product_id == $this->id) {
                return $vendor->pivot->vat;
            }
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'quantity', 'price', 'subject'])
            ->logOnlyDirty()
            ->useLogName('Product')
            ->dontSubmitEmptyLogs();
    }
}

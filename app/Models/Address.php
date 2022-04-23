<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'present_address',
        'email',
        'city',
        'website',
        'state',
        'zip',
    ];

    /**
     * Get the parent commentable model (post or video).
     */
    public function addressable()
    {
        return $this->morphTo();
    }
}

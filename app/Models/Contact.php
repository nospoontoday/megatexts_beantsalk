<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mobile',
        'landline',
    ];

    /**
     * Get the parent commentable model (post or video).
     */
    public function contactable()
    {
        return $this->morphTo();
    }
}

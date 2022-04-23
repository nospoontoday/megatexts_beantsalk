<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function printBooks()
    {
        return $this->hasMany(PrintBook::class);
    }

    public function AVMaterials()
    {
        return $this->hasMany(AVMaterial::class);
    }

    public function eBooks()
    {
        return $this->hasMany(EBook::class);
    }
}

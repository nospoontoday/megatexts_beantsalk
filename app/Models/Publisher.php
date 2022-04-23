<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
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

    public function eJournals()
    {
        return $this->hasMany(EJournal::class);
    }

    public function onlineDatabases()
    {
        return $this->hasMany(OnlineDatabase::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
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

    public function onlineDatabases()
    {
        return $this->hasMany(OnlineDatabase::class);
    }

    public function eJournals()
    {
        return $this->hasMany(EJournal::class);
    }

    public function eBooks()
    {
        return $this->hasMany(EBook::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editor extends Model
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

    public function printJournals()
    {
        return $this->hasMany(PrintJournal::class);
    }

    public function eJournals()
    {
        return $this->hasMany(EJournal::class);
    }
}

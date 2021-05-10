<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
 use App\Models\Family;
class People extends Model
{

    protected $connection = 'mongodb';

    protected $collection = 'people';
       

    protected $with = [
        "owner",
        // "familymembers"
    ];

    public function owner()
    {
        return $this->belongsTo(Family::class,'family_owner_id');
    }
    // public function familymembers()
    // {
    //     return $this->hasMany(People::class,'family_id1');
    // }
}

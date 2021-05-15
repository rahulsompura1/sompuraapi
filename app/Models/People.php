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
        "spouse"
    ];

    public function owner()
    {
        return $this->belongsTo(Family::class,'family_owner_id');
    }
    public function spouse()
    {
        return $this->belongsTo(People::class,'spouce_id');
    }
}

<?php

namespace App\models;

use Jenssegers\Mongodb\Eloquent\Model;

use App\People;
 
class Family extends Model
{

    protected $connection = 'mongodb';

    protected $collection = 'families';

    protected $with = [
        "owner"
    ];

    public function owner()
    {
        return $this->hasOne(People::class,'family_owner_id');
    }

       
}

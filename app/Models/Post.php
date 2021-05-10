<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
 use App\Family;
class Post extends Model
{

    protected $connection = 'mongodb';

    protected $collection = 'post';
       
  

   
    // public function familymembers()
    // {
    //     return $this->hasMany(People::class,'family_id1');
    // }
}

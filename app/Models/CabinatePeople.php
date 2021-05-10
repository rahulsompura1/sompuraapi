<?php

namespace App\models;

use Jenssegers\Mongodb\Eloquent\Model;


 
class CabinatePeople extends Model
{

    protected $connection = 'mongodb';

    protected $collection = 'cabinate_roles';
  

       
}

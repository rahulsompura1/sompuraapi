<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;


 
class Cabinate extends Model
{

    protected $connection = 'mongodb';

    protected $collection = 'cabinets';
  

       
}

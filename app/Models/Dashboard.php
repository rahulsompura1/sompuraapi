<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;


 
class Dashboard extends Model
{

    protected $connection = 'mongodb';

    protected $collection = 'dashboard_menu';
  

       
}

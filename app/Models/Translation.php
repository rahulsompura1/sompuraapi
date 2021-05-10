<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;
    protected $fillable = [
    	"key",
    	"value",
    	"language",
    ];
}

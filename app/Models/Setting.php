<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    
    protected $fillable = [
    	"pdf_name",
    	"store_name",
    	"welcome_message",
        "company_id",
        "store_slug"
    ];
}

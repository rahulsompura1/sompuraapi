<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class FormFields extends Model
{
    use HasFactory;

     protected $fillable = [
        "user_id",
        "name",
        "required",
        "type",
        "options",
        'pdfname'
    ];
}

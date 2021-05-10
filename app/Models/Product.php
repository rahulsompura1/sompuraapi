<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\Category;
class Product extends Model
{
    use HasFactory;

    public $with = [
        'category',
    ];

    protected $fillable = [
        "category_id",
        "name",
        "images",
        "company_id",
        "description",
        "prices",
        "company_id",
    ];

    public $rules = [
        "name" => "required|min:2|max:191",
        "category" => "required"
    ];

    public function category() {
        return $this->hasOne(Category::class,'_id','category_id');
    }
}
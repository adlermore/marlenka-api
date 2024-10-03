<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function images(){
        return $this->hasMany(ProductImages::class,"product_id","id");
    }
    public function technicals(){
        return $this->hasMany(ProductTechnicals::class,"product_id","id");
    }
    public function category(){
        return $this->hasOne(Categories::class,"id","category_id");
    }
}

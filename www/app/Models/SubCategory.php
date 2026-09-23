<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $gaurded = [];
    protected $table = 'subcategories';
     protected $fillable = [
        'name',
        'category_id',
        'image',
        'status',
        'description',
        'banner',
        'icon',
        'slug',
        ];
    
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
}

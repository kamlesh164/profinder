<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    
    protected $fillable = ['title','slug','image','description','seo_title','seo_description'];

}

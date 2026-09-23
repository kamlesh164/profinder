<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SquareFoot extends Model
{
    protected $table = 'square_feet';
    protected $fillable = ['title','from_area','to_area','price','price_multiply'];
    

}

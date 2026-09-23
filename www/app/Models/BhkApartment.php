<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BhkApartment extends Model
{
    protected $table = 'bhk_apartments';
    protected $fillable = ['title','bhk','price_multiply'];
}

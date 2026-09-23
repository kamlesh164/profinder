<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['name' ,'price','duration','icon','description','status'];
    // public function cities(){
    //     return $this->hasMany(City::class, 'state_id');
    // }
}

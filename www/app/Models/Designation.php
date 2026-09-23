<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'designations';
    protected $fillable = ['name','status'];
    public function user(){
        return $this->hasOne(User::class,'designation');
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class City extends Model
{
    protected $table = 'cities';
    protected $fillable = ['state_id','name','code'];
    
    public function state(){
        return $this->belongsTo(State::class,'state_id');
    }
    public function services(){
        return $this->hasMany(Service::class);
    }
    public function user(){
        return $this->hasMany(User::class);
    }
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords(strtolower($value)),
        );
    }
    
}

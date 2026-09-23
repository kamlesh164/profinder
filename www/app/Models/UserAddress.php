<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = ['user_id','house_no','area','city','state','pincode','address','is_default','latitude','longitude','address_type'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function cityRelation(){
        return $this->belongsTo(City::class, 'city');
    }
    public function stateRelation(){
        return $this->belongsTo(State::class, 'state');
    }
}

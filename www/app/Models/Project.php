<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['service_id','name','user_id','customer_name','customer_mobile','customer_email','budget','duration','start_date','gender','house_no','area','pincode','city','state','image'];

    public function service(){
        return $this->belongsTo(Service::class,'service_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function bids(){
        return $this->hasMany(Bid::class,'project_id');
    }
}

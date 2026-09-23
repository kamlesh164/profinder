<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRating extends Model
{
    protected $fillable = ['service_id', 'booking_id','user_id','rating','review'];

    public function service(){
        return $this->belongsTo(Service::class,'service_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function booking(){
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
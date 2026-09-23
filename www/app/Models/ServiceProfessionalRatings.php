<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProfessionalRatings extends Model
{
    protected $fillable = ['sp_id', 'booking_id','user_id','rating','review'];

    public function serviceProfessional(){
        return $this->belongsTo(User::class, 'sp_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function booking(){
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}

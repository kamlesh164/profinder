<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ServiceProfessional extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $table = 'service_professional';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'address',
        'city',
        'state',
        'website',
        'otp',
        'otp_status',
        'status',
        'otp_valid_till',
        'password',
        'vendor_type',
    ];

    public function cityRelation(){
        return $this->belongsTo(City::class,'city');
    }
    public function stateRelation(){
        return $this->belongsTo(State::class,'state');
    }
    public function services(){
        return $this->hasMany(Service::class);
    }
}

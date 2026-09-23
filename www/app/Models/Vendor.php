<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Helpers\StaticDataHelper;

class Vendor extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;
    
    protected $table = 'vendors';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'pincode',
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

    public function kycDetail(){
        return $this->hasOne(VendorKyc::class,'vendor_id');
    }

    public function products(){
        return $this->hasMany(Product::class,'vendor_id');
    }

    public function bookings(){
        return $this->hasMany(Booking::class,'vendor_id');
    }
}

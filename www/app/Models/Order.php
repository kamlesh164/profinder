<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id','name','email','phone','timeslot','booking_date','city','state','pincode',
    'total_amount','payment_method','payment_status','transaction_id','order_status','cart_qty'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function orderItem(){
        return $this->hasMany(OrderItem::class);
    }
}

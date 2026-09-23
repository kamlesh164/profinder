<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseHistory extends Model
{
    protected $fillable = ['user_id','plan_id','amount','bids','starts_at',
    'ends_at','payment_gateway','payment_id','order_id','status'];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
    ];

    public function plan(){
        return $this->belongsTo(SubscriptionPlan::class,'plan_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function paymentTransaction(){
        return $this->hasOne(PaymentTransaction::class,'payment_id', 'payment_id');
    }
    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    public function scopeActive($q)
    {
        return $q->where('status', config('static_data.status.ACTIVE'))
                 ->where('ends_at', '>', now());
    }
    public function scopeExpired($q)
    {
        return $q->where('status', config('static_data.status.ACTIVE'))
                 ->where('ends_at', '<=', now());
    }
    public function scopeExpiringSoon($q, $days = 3)
    {
        return $q->where('status', config('static_data.status.ACTIVE'))
                 ->whereBetween('ends_at', [now(), now()->addDays($days)]);
    }
}

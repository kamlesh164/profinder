<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $table = 'payment_transactions';
    protected $fillable = [
        'booking_id',
        'user_id',
        'reference_id',
        'reference_type',
        'amount',
        'currency',
        'order_id',
        'payment_id',
        'status',
        'payment_method',
        'error_message',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

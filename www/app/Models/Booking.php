<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    
    protected $fillable = [
        'user_id',
        'sp_id',
        'service_id',
        'slot_date',
        'slot_time',
        'property_type',
        'property_size',
        'qty',
        'base_price',
        'notes',
        'address',
        'tax',
        'total_amount',
        'status',
        'contractor_response_status',
        'reject_reason',
        'payment_status',
        'payment_id',
    ];

    protected $casts = [
        'slot_date' => 'date',
        'slot_time' => 'datetime:H:i:s',
        'tax' => 'float',
        'total_amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contractor()
    {
        return $this->belongsTo(User::class, 'sp_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(BookingStatusLog::class);
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    // Helper methods
    public function canBeApprovedByContractor()
    {
        return $this->status === config('static_data.status.PENDING');
    }

    public function canBeRejectedByContractor()
    {
        return $this->status === config('static_data.status.PENDING');
    }
    public function canBeRejected()
    {
        return $this->status === config('static_data.status.PENDING');
    }
    public function canPayment()
    {
        return $this->status === config('static_data.status.PMT_PENDING') && $this->payment_status === config('static_data.status.PENDING');
    }
}

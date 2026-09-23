<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',     // customer
        'sp_id',       // service professional
        'last_message_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class,'sp_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }
}

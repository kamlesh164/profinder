<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobBid extends Model
{
    protected $fillable = [
        'job_post_id',
        'sp_id',
        'amount',
        'message',
        'status'
    ];
    public function jobpost(){
        return $this->belongsTo(JobPost::class,'job_post_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'sp_id');
    }
}

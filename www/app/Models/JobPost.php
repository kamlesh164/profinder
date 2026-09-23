<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    protected $fillable = [
        'user_id',
        'service_category_id',
        'city_id',
        'image',
        'title',
        'budget',
        'duration',
        'address',
        'description',
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function servicecategory(){
        return $this->belongsTo(ServiceCategory::class,'service_category_id');
    }
    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
    public function bids(){
        return $this->hasMany(JobBid::class, 'job_post_id');
    }
}

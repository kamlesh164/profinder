<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $table = 'subscription_plans';
    protected $fillable = ['plan_name','plan_price','expiration_date','status','bids','skills','serial','features_json'];
    protected $casts = [
        'features_json' => 'array',
    ];
    public function purchaseHistories(){
        return $this->hasMany(PurchaseHistory::class,'plan_id');
    }
}

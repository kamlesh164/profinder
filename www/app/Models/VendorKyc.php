<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorKyc extends Model
{
    protected $fillable = ['vendor_id','adhar_no','pancard_no','adhar_front','adhar_back','pancard','company_name','gst_number','gst_doc','license','status','reject_reason'];

    public function vendor(){
        return $this->belongsTo(Vendor::class,'vendor_id');
    }
}

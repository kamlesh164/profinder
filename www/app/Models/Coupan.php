<?php

namespace App\Models;

use App\Enum\CoupanEnum;
use Illuminate\Database\Eloquent\Model;

class Coupan extends Model
{
    protected $fillable = ['coupan_code','value','type','coupan_for','is_active','expires_at'];

}

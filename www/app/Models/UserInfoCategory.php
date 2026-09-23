<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfoCategory extends Model
{
    protected $table = "user_info_category";
    protected $fillable = ['user_id', 'info_category_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(InfoCategory::class, 'info_category_id');
    }
}

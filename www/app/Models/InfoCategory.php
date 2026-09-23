<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoCategory extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_info_category',
            'info_category_id',
            'user_id'
        );
    }
}

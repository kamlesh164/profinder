<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','project_id','amount','is_approve','status'];

    public function project(){
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}

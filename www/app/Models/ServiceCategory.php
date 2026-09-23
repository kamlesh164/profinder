<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\StaticDataHelper;

class ServiceCategory extends Model
{
    protected $fillable = ['name','slug','image','commission','description','is_active'];

    private static $instance = null;
    public static function Obj()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function service(){
        return $this->hasMany(Service::class,'service_category_id');
    }
    public function getAll($columns = ['*'],$limit = false){

        $query = self::where('is_active', 1)
        ->orderBy('created_at', 'desc')
        ->select($columns);

        if ($limit !== false) {
            $query->limit($limit);
        }

        $data = $query->get();
        $data = StaticDataHelper::Obj()->mapImageUrl($data, 'image');
        return $data->toArray();
    }
}

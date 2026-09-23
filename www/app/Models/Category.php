<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\StaticDataHelper;

class Category extends Model
{
    protected $gaurded = [];
    protected $fillable = [
        'name',
        'image',
        'status',
        'description',
        'banner',
        'icon',
        'slug',
        'commission',
        ];
    private static $instance = null;
    public static function Obj()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function subcategories(){
        return $this->hasMany(SubCategory::class,'category_id');
    }
    public function products(){
        return $this->hasMany(Product::class,'category_id');
    }
    public function getAll($columns = ['*'],$limit = false){

        $query = self::where('status', 1)
        ->orderBy('created_at', 'desc')
        ->select($columns);

        if ($limit !== false) {
            $query->limit($limit);
        }

        $data = $query->get();
        $data = StaticDataHelper::Obj()->mapImageUrl($data, 'icon');
        return $data->toArray();
    }

}

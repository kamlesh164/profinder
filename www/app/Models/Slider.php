<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\StaticDataHelper;

class Slider extends Model
{
    private static $instance = null;
    public static function Obj()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    protected $fillable = [
        'name',
        'image',
        'link',
        'is_active',
        'type',
    ];
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
    public function getAll($param = [],$limit = 3){
        $query = Slider::select(['id','image']);
        if(isset($param['type'])){
            $query->where('type',$param['type']);
        }
        $data = $query->orderBy('created_at','desc')->paginate($limit);
        $data = StaticDataHelper::Obj()->mapImageUrl($data,'image');
        return $data->toArray();
    }
}

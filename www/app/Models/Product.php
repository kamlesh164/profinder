<?php

namespace App\Models;

use App\Helpers\StaticDataHelper;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['sp_id','category_id','city_id','name','price','qty','image','description','specs','is_active','approve_admin'];

    public function serviceProfessional(){
        return $this->belongsTo(User::class, 'sp_id');
    }
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function wishlist(){
        return $this->hasMany(Wishlist::class, 'product_id');
    }
    public function ratings(){
        return $this->hasMany(ProductRating::class);
    }
    public static function getAllProductsByParam($param = []){
        $query = Product::query();

        if (!empty($param['userId'])) {
            $query->where('sp_id', $param['userId']);
        }

        if (!empty($param['categoryId'])) {
            $query->where('category_id', $param['categoryId']);
        }

        if (!empty($param['cityId'])) {
            $query->where('city_id', $param['cityId']);
        }
        $query->where('is_active', 1)
            ->orderBy('name');

        $products = $query->get();
        $data = StaticDataHelper::Obj()->addImageUrl($products, 'image');
        return $data->toArray();
    }
    public static function getProductDetailsById($id) {
        $product = self::withAvg('ratings as avg_rating', 'rating')
        ->withCount('ratings')->find($id);
        if (!$product) return null;
        $data = StaticDataHelper::Obj()->mapImageUrl(collect([$product]), 'image');
        return $data->first();
    }
}

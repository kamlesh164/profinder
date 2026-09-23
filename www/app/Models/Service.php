<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\StaticDataHelper;

class Service extends Model
{
    protected $fillable = ['sp_id','service_category_id','state_id','city_id','name','slug','image','base_price','description','is_active'];
    protected $casts = [
        'avg_rating' => 'decimal:1'
    ];
    public function project(){
        return $this->hasMany(Project::class, 'service_id');
    }
    public function serviceCategory(){
        return $this->belongsTo(ServiceCategory::class);
    }
    public function services() {
        return $this->hasMany(Service::class, 'service_category_id');
    }
    public function bookings(){
        return $this->hasMany(Booking::class, 'service_id');
    }
    public function state(){
        return $this->belongsTo(State::class,'state_id');
    }
    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
    public function serviceProfessional(){
        return $this->belongsTo(User::class,'sp_id');
    }
    public function ratings(){
        return $this->hasMany(ServiceRating::class, 'service_id');
    }
    public static function getServicesByCityName($cityName, $limit=false){
        $query = self::with('city')
                ->whereHas('city', function($query) use ($cityName) {
                    $query->where('name', $cityName);
                });
        if ($limit !== false) {
            $query = $query->limit($limit);
        }

        $data = $query->get();
        $data = StaticDataHelper::Obj()->mapImageUrl($data,'image');
        return $data->toArray();
    }
    public static function getFeaturedServicesByCityName($cityName, $limit = false){
        $query = self::with(['city'])
        ->whereHas('city', function ($q) use ($cityName) {
            $q->where('name', $cityName);
        })
        ->withAvg('ratings as avg_rating', 'rating')
        ->withCount('ratings')
        ->orderByDesc('avg_rating')
        ->orderByDesc('ratings_count');

        if ($limit !== false) {
            $query = $query->limit($limit);
        }

        $services = $query->get();
        $services = StaticDataHelper::Obj()->mapImageUrl($services, 'image');
        $services->makeHidden(['city']);

        return $services->toArray();

    }
    public static function getServicesByCategoryIdAndCityName($categoryId,$cityName) {
        $services = Service::with(['city', 'serviceCategory'])
        ->where('service_category_id', $categoryId)
        ->whereHas('city', function($query) use ($cityName) {
            $query->where('name', $cityName);
        })
        ->get();
        $data = StaticDataHelper::Obj()->mapImageUrl($services, 'image');
        return $data->makeHidden(['serviceCategory'])->toArray();
    }
    public static function getServiceDetailsById($id) {
        $service = self::with(['city'])->withAvg('ratings as avg_rating', 'rating')
        ->withCount('ratings')->find($id);
        if (!$service) return null;
        $data = StaticDataHelper::Obj()->mapImageUrl(collect([$service]), 'image');
        return $data->first();
    }
    public static function getAllServicesOfSpByParam($param = []){
        $query = Service::with(['serviceCategory']);
        
        if(isset($param['userId'])){
            $query->where('sp_id', $param['userId']);
        }
        
        if(isset($param['cityId'])){
            $query->where('city_id', $param['cityId']);
        }

        if(isset($param['categoryId'])){
            $query->where('service_category_id', $param['categoryId']);
        }

        $services = $query->where('is_active', 1)
            ->orderBy('created_at','DESC')
            ->get();
        $data = StaticDataHelper::Obj()->addImageUrl($services, 'image');
        return $data->toArray();
    } 
}

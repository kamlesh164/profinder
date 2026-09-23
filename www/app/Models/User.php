<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\StaticDataHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements JWTSubject
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $appends = ['image_url'];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'dob',
        'image',
        'city_id',
        'type',
        'is_verified',
        'is_new_user',
        'current_plan_id',
        'current_plan_ends_at',
        'current_bids',
        'profile_completed',
        'website',
        'otp',
        'otp_status',
        'otp_valid_till',
        'password',
        'source'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->image) {
                return null;
            }
            return asset($this->image); 
        });
    }

    public function address()
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'user_id');
    }
    public function order()
    {
        return $this->hasMany(Order::class);
    }
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }
    public function receivedRatings()
    {
        return $this->hasMany(ServiceProfessionalRatings::class, 'sp_id');
    }
    public function givenRatings()
    {
        return $this->hasMany(ServiceProfessionalRatings::class, 'user_id');
    }
    public function infoCategories(){
        return $this->hasMany(UserInfoCategory::class);
    }
    public function categories()
    {
        return $this->belongsToMany(
            InfoCategory::class,
            'user_info_category', 
            'user_id', 
            'info_category_id' 
        );
    }
    public static function getSingleUser($id)
    {
        return self::where('id', '=', $id)
            ->first();
    }
    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public static function getServiceProfessionalUsers(){
        return self::where('is_verified', 1)
           ->whereIn('type', [
               config('static_data.accountType.contractor'),
               config('static_data.accountType.vendor'), 
               config('static_data.accountType.freelancer')
           ])
           ->get();
    }
    
    public static function getPopularServiceProfessionalsByCityname($cityName, $limit = false){
        $query = self::query()
            ->whereIn('type', [2,3,4])
            ->whereHas('address', function ($q) use ($cityName) {
                $q->where('is_default', 1)
                ->whereHas('cityRelation', function ($c) use ($cityName) {
                    $c->whereRaw('LOWER(name) = ?', [strtolower($cityName)]);
                });
            })
            ->withAvg('receivedRatings as avg_rating', 'rating')
            ->withCount('receivedRatings')
            ->orderByDesc('avg_rating')
            ->orderByDesc('received_ratings_count'); 

        if ($limit !== false) {
            $query->limit($limit);
        }

        $sps = $query->get();

        $sps = StaticDataHelper::Obj()->mapImageUrl($sps, 'image');

        return $sps->toArray();
    }
}

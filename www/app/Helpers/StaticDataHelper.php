<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class StaticDataHelper{
    private static $instance = null;
    public static function Obj()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public static function isAccountTypeValid($type){
        $allowedTypes = config('static_data.accountType');
        return in_array(strtolower($type), $allowedTypes);
    }
    public function mapImageUrl($collection, $field = 'image'){
        if (is_array($collection)) {
            $collection = collect($collection);
        }
        return $collection->transform(function ($item) use ($field) {
            $item['image_url'] = self::getImageUrl($item[$field]);
            return $item;
        });
    }
    public function addImageUrl($data, $field = 'image'){
        return $data->map(function ($service) use ($field) {
            $path = $service->{$field} ?? null;

            $service->image_url = $path
                ? url($path) 
                : null;

            return $service;
        });
    }
    public function getImageUrl($image){
        if ($image) {
            return url($image);
        }
        return null;
    }
    public static function getAccountTypeLabel($id)
    {
        $map = array_flip(config('static_data.accountType'));
        return $map[$id] ?? 'N/A';
    }
    public static function imageOrDefault(?string $path, $type = null,string $default = 'uploads/no-photo.png'): string{
        if (empty($path)) {
            return $type == 'user' ? asset('uploads/no-user.svg') : asset($default);
        }
        $fullPath = public_path($path);
        if (! File::exists($fullPath)) {
            return asset($default);
        }
        return asset($path);
    }
    public static function createRazorpayOrder(float $amount, string $receipt): array
    {
        try {

            $config = config('app.razorpay');
            $key_id = $config['app_id'];
            $key_secret = $config['secret_key'];
            
            $api = new Api($key_id, $key_secret);

            $order = $api->order->create([
                'amount'   => (int) round($amount * 100),
                'currency' => 'INR',
                'receipt'  => $receipt,
            ]);

            return [
                'success' => true,
                'order'   => $order->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('Razorpay create order error', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }
}
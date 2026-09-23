<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ServiceRating;
use Illuminate\Http\Request;

class ApiServiceRatingController extends BaseApiController
{
    public function getCustomerReviews(Request $request){
        try {
            $limit = 10;
            $ratings = ServiceRating::with(['service:id,name,image', 'user:id,name,image'])
                ->orderByDesc('rating')
                ->orderByDesc('created_at')
                ->get([
                    'id','service_id','user_id','rating','review','created_at'
                ]);

            $seenUserIds = [];
            $unique = $ratings->filter(function ($rating) use (&$seenUserIds) {
                if (in_array($rating->user_id, $seenUserIds)) {
                    return false; 
                }
                $seenUserIds[] = $rating->user_id;
                return true;
            })->take($limit);

            $data = $unique->map(function ($rating) {
                return [
                    'service_id'      => $rating->service->id,
                    'service_name'    => $rating->service->name,
                    'service_image'   => asset($rating->service->image),
                    'review_id'       => $rating->id,
                    'user_id'         => $rating->user->id,
                    'user_name'       => $rating->user->name,
                    'user_image'      => $rating->user->image ? asset($rating->user->image) : null,
                    'rating'          => (int) $rating->rating,
                    'review'          => $rating->review,
                    'created_at'      => $rating->created_at->toDateTimeString(),
                ];
            });
            
            return $this->successResponse($data, 'Reviews fetched successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
